<?php
namespace IfSo\Addons\Geolocation\PostExclude;
class PostExcluder{
    public function exclude_saved_posts():void {
        if(is_admin() && !(defined('DOING_AJAX') && DOING_AJAX)) return;
        $all_excludes = $this->get_excluded_posts_data();
        if(!empty($all_excludes)){
            foreach ($all_excludes as $exclude){
                $this->exclude_post_categories_by_location($exclude);
            }
        }
    }
    /** @return ExcludePostData[]  **/
    public function get_excluded_posts_data():array{
        /* @var $ret ExcludePostData[] */
        $ret = [];
        $hide_posts_data = json_decode(\IfSo\Services\PluginSettingsService\PluginSettingsService::get_instance()->extraOptions->geolocation['hidePostsByGeo']->get()) ;
        if(!empty($hide_posts_data)){
            foreach($hide_posts_data as $exclude_data){
                if(!empty($exclude_data->showhide) && !empty($exclude_data->location_type) && !empty($exclude_data->location) && !empty($exclude_data->categories)){
                    $tax_term = explode('||',$exclude_data->categories);
                    $categories = [$tax_term[1]];
                    $combined_key = $exclude_data->showhide . '-' . $exclude_data->location_type . '-' . $exclude_data->location . '-' . $tax_term[0];
                    if(empty($ret[$combined_key]))
                        $ret[$combined_key] = new ExcludePostData($exclude_data->showhide,$exclude_data->location_type,$exclude_data->location,$tax_term[0],$categories,
                            !empty($exclude_data->rdr_url) ? $exclude_data->rdr_url : null);
                    else
                        $ret[$combined_key]->add_categories($categories);
                }
            }
        }
        return array_values($ret);
    }
    public function exclude_post_categories_by_location(ExcludePostData $exclude):void{
        add_action( 'pre_get_posts', function($query)use($exclude){			//Hide all of the posts from the chosen categories - basic WP query - like search
            if(/*$query->get('post_type')!=='post_type'*/!$exclude->location_needs_exclusion()) return;
            $tax_query = [[
                'taxonomy' => $exclude->taxonomy,
                'terms' => $exclude->exclude_categories,
                'field' => 'term_id',
                'operator' => 'NOT IN',
            ]];
            //$tax_query[0]['operator'] = $exclude->exclude_type==='show' ? 'IN' : 'NOT IN';
            $query->set( 'tax_query', $tax_query);
        } );
        add_action('template_redirect',function()use($exclude){		//Hide all of the posts from the relevant categories when the user is in the country - single product page
            if(!is_single() || !$exclude->location_needs_exclusion()) return;
            $terms = get_the_terms(get_the_ID(),$exclude->taxonomy);
            if(empty($terms)) return;
            $product_cats = array_map(function($term){return $term->term_id;},$terms);
            if($exclude->product_cats_match_exclude($product_cats) && $exclude->rdr_url!==null){
                wp_redirect($exclude->rdr_url);
            }
        });
    }
}

class ExcludePostData {
    public string $exclude_type;
    public string $location_type;
    public string $location_value;
    public string $taxonomy;
    public array $exclude_categories;
    public ?string $rdr_url;
    public function __construct($exclude_type,$location_type,$location_value,$taxonomy,$exclude_categories,$rdr_url) {
        $this->exclude_type = $exclude_type;
        $this->location_type = $location_type;
        $this->location_value = $location_value;
        $this->taxonomy = $taxonomy;
        $this->exclude_categories = $this->ensure_types($exclude_categories);
        $this->rdr_url = $rdr_url;
    }

    public function add_categories($new_cats) {
        $this->exclude_categories = $this->ensure_types(array_merge($this->exclude_categories,$new_cats));
    }

    public function location_matches_exclude():bool {
        require_once(IFSO_PLUGIN_BASE_DIR. 'services/geolocation-service/geolocation-service.class.php');
        $geo_data = \IfSo\Services\GeolocationService\GeolocationService::get_instance()->get_user_location();
        return ($geo_data->get($this->location_type)===$this->location_value);
    }

    public function location_needs_exclusion():bool{
        if($this->location_matches_exclude())
            return $this->exclude_type==='hide';
        else
            return $this->exclude_type==='show';
    }

    public function product_cats_match_exclude($product_cats):bool{
        return (!empty(array_intersect($this->exclude_categories,$product_cats)));
    }

    protected function ensure_types($arr) {
        return array_map(function($el){
            if(is_numeric($el)) return (int) $el;
            else return $el;
        },$arr);
    }
}

class ExcludeCategory{
    public $taxonomy;
    public $term_id;
    public function __construct($taxonomy,$term_id) {
        $this->taxonomy = $taxonomy;
        $this->term_id = $term_id;

    }
}