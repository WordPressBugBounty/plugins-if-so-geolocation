<?php
namespace IfSo\Addons\Geolocation\Services;

use IfSo\Services\PluginSettingsService;

class GeoRequestLogService{
    private static $instance;
    private string $default_log_location = IFSO_PLUGIN_BASE_DIR . "logs/";
    private string $log_file_name = 'geo_request.log';
    private string $log_location;
    private int $last_processed_line = 0;
    public int $minimum_ip_occurrences_for_suspicion = 15;
    private int $suspicious_ips_amount = 0;

    public static function get_instance(){
        if(empty(self::$instance))
            self::$instance = new static();
        return self::$instance;
    }

    protected function __construct(){
        if(!empty(wp_get_upload_dir()) && !empty(wp_get_upload_dir()['basedir'])){
            $upload_dir = wp_get_upload_dir()['basedir'] . '/if-so/';
            if(!is_dir($upload_dir))
                wp_mkdir_p($upload_dir);
            $this->log_location = $upload_dir . $this->log_file_name;
        }
        else
            $this->log_location = $this->default_log_location. $this->log_file_name;
    }

    public function set_log_location($loc){
        $this->log_location = $loc;
    }

    public function get_last_processed_line(){
        return $this->last_processed_line;
    }

    public function get_log_location(){
        return $this->log_location;
    }

    private function get_file_lines($h){
        while (!feof($h)) {
            yield fgets($h);
        }
    }

    public function process_log($callback){
        if(!file_exists($this->log_location)) return;
        try {
            $h = fopen($this->log_location, 'r');
            if($h){
                foreach ($this->get_file_lines($h) as $line) {
                    $callback($line);
                    $this->last_processed_line++;
                }
                fclose($h);
            }
        }
        catch (\Exception $e){}
    }

    private function count_logline_occurence($line,&$occurences){
        preg_match('/\(ip\:([^\)]*)/', $line, $ip_matches);
        $ip = !empty($ip_matches[1]) ? $ip_matches[1] : null;
        if($ip!==null){
            if(!isset($occurences[$ip]))
                $occurences[$ip] = 1;
            else
                $occurences[$ip]++;

            if($occurences[$ip]===$this->minimum_ip_occurrences_for_suspicion)
                $this->suspicious_ips_amount++;
        }
    }

    public function find_suspicious_ips($find_all=false){
        $this->suspicious_ips_amount = 0;
        $occurences = [];
        $count_ip_occurences = function ($line) use (&$occurences){$this->count_logline_occurence($line,$occurences);};
        $this->process_log($count_ip_occurences);

        arsort($occurences,SORT_NUMERIC );

       if(!$find_all)
            $occurences = array_slice($occurences,0,$this->suspicious_ips_amount);

        return $occurences;
    }

    private function get_occurence_data_from_logline($ip,$line,&$occurences){
        preg_match('/\[(.+)\].*\(ip\:(.+)\).*\(UA\:(.+)\).*\(URL\:(.+)\).*\-(.+)/', $line, $line_data);
        if(!empty($line_data[1]) && !empty($line_data[2]) && !empty($line_data[3]) && !empty($line_data[4]) && $ip===$line_data[2])
            $occurences[] = ['date'=>$line_data[1],'ip'=>$line_data[2],'user-agent'=>$line_data[3],'url'=>$line_data[4],'status'=>trim($line_data[5])];
    }

    public function find_ip_occurences($ip){
        $occurences = [];
        $get_ip_occurences = function ($line) use (&$occurences,$ip){$this->get_occurence_data_from_logline($ip,$line,$occurences);};
        $this->process_log($get_ip_occurences);

        return $occurences;
    }

    public function log_geo_request($ip,$success){
        if(PluginSettingsService\PluginSettingsService::get_instance()->extraOptions->geolocation['logGeoRequests']->get()){
            try{
                $uAgent = !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown';
                $now_str = date('d-M-Y H:i:s');
                $success_str = $success ? "SUCCESS" : "FAIL";
                $request = \IfSo\PublicFace\Services\AjaxTriggersService\AjaxTriggersService::get_instance()->get_request();
                $request_url = !empty($request) ? $request->getRequestURL() : '';
                $logline = "[{$now_str}] - GeoIp request from (ip:{$ip}) - (UA:{$uAgent}) - (URL:{$request_url}) - {$success_str}" . PHP_EOL;
                $file = fopen($this->log_location,"a+");
                fwrite($file, $logline);
                fclose($file);
            }
            catch (\Exception $e){
                error_log('Error logging If-So Geolocation request! ' . $e->getMessage());
            }

        }

    }

}
