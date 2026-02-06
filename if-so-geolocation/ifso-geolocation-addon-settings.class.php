<?php
namespace IfSo\Addons\Geolocation;

use IfSo\Services\PluginSettingsService;

if(class_exists('\IfSo\Addons\Base\Settings')){
    class Settings extends \IfSo\Addons\Base\Settings{
        public function print_extra_settings_ui_geolocation(){
            $settingsService = PluginSettingsService\PluginSettingsService::get_instance();
            $log_analyzer_url = admin_url("?page=wpcdd_ifso_geo_log_analyzer");
            ?>
            <tr valign="top">
                <td class="ifso-settings-td" scope="row" valign="baseline">
                    <b><?php _e('Log geolocation requests', 'if-so'); ?></b>
                </td>
                <td valign="baseline">
                    <input
                            type="checkbox"
                        <?php echo ($settingsService->extraOptions->geolocation['logGeoRequests']->get() ? "CHECKED" : ""); ?>
                            name="ifso_geolocation_logGeoRequests"
                            type="text"
                            class="ifso_settings_page_option"
                            value="log_geo_requests" />
                    <i><?php _e("Enable this option if the geolocation count doesn’t seem to match the number visits on your 3rd-party analytics reports. The log will help you identify IPs of bots visiting your site and block them from the geolocation service: ", 'if-so'); ?><a href="https://www.if-so.com/faq-items/the-geolocation-session-count-doesnt-seem-to-behave-as-expected/?utm_source=Plugin&utm_medium=settings&utm_campaign=geolocation_log" target="_blank">Learn More</a> </i>
                </td>
            </tr>
            <tr valign="top">
                <td class="ifso-settings-td" scope="row" valign="baseline"></td>
                <td valign="baseline">
                    <a onclick="window.open('<?php echo esc_url($log_analyzer_url); ?>', 'newwindow', 'width=800,height=900'); return false;" href="<?php echo esc_url($log_analyzer_url); ?>" target="">Analyze Geolocation Request Log</a>
                </td>
            </tr>
            <tr valign="top">
                <td class="ifso-settings-td" scope="row" valign="baseline">
                    <b><?php _e('Block Bots (Beta)', 'if-so'); ?></b>
                </td>
                <td valign="baseline">
                    <input
                            type="checkbox"
                        <?php echo ($settingsService->extraOptions->geolocation['blockGeoBots']->get() ? "CHECKED" : ""); ?>
                            name="ifso_geolocation_blockGeoBots"
                            type="text"
                            class="ifso_settings_page_option"
                            value="log_geo_requests" />
                    <i><?php _e('Prevent Search Engine Crawlers, CURL Requests, and other bots from accessing the geolocation service. Default content will be displayed regardless of the bot\'s location. <a href="https://www.if-so.com/faq-items/the-geolocation-session-count-doesnt-seem-to-behave-as-expected/#searchEngines">Learn more</a>.'); ?> </i>
                </td>
            </tr>
            <tr valign="top">
                <td class="ifso-settings-td" scope="row" valign="baseline">
                    <b><?php _e('Browser-Based Location', 'if-so'); ?></b>
                </td>
                <td valign="baseline">
                    <i><?php _e("Serve location-based content by utilizing the browser's Geolocation API (HTML5). This method is more accurate than the default IP-to-location approach, but it does require obtaining users' consent to access their location.", 'if-so'); ?> <a href="https://www.if-so.com/the-html-geolocation-api/?utm_source=Plugin&utm_medium=settings&utm_campaign=geolocation&utm_term=learnMore" target="_blank">Learn More</a></i> <br><br> Choose when to request access to the user's location: <br><br>
                    <input style="margin-top:0;margin-right:6px;" type="radio" name="ifso_geolocation_browserLocationMode" value="0" <?php echo (int) $settingsService->extraOptions->geolocation['browserLocationMode']->get() === 0 ? "CHECKED" : ''; ?>><label style="line-height: 1.7;">Never</label><br>
                    <input style="margin-top:0;margin-right:6px;" type="radio" name="ifso_geolocation_browserLocationMode" value="1" <?php echo (int) $settingsService->extraOptions->geolocation['browserLocationMode']->get() === 1 ? "CHECKED" : ''; ?>><label style="line-height: 1.7;">When the user encounters the shortcode [ifso_get_browser_location]</label><br>
                    <input style="margin-top:0;margin-right:6px;" type="radio" name="ifso_geolocation_browserLocationMode" value="2" <?php echo (int) $settingsService->extraOptions->geolocation['browserLocationMode']->get() === 2 ? "CHECKED" : ''; ?>><label style="line-height: 1.7;">Whenever the user encounters geo-targeted content</label><br>
                    <input style="margin-top:0;margin-right:6px;" type="radio" name="ifso_geolocation_browserLocationMode" value="3" <?php echo (int) $settingsService->extraOptions->geolocation['browserLocationMode']->get() === 3 ? "CHECKED" : ''; ?>><label style="line-height: 1.7;">Every time the user visits the site</label<br>
                </td>
            </tr>
            <tr valign="top">
                <td class="ifso-settings-td" scope="row" valign="baseline">
                    <b><?php _e("Post Visibility by Location", 'if-so'); ?></b>
                </td>
                <td class="">
                    <i><?php _e("Show/hide category posts by visitor location",'if-so'); ?></i>
                    <textarea name="ifso_geolocation_hidePostsByGeo" class="ifso_settings_page_option" style="width:100%;"
                    ><?php echo $settingsService->extraOptions->geolocation['hidePostsByGeo']->get(); ?></textarea>

                    <div class="hidePostsByGeo-ui">
                        <style>
                            .hidePostsByGeo-titles .hidePostsByGeo-title{
                                font-weight: bold;
                                font-weight: 600;
                            }
                            .hidePostsByGeo-location,.hidePostsByGeo-titles{
                                display:flex;
                                margin-bottom:5px;
                                justify-content: space-between;
                                align-items:baseline;
                            }
                            .hidePostsByGeo-titles{
                                align-items:end;
                                margin-top:16px;
                            }
                            textarea[name="ifso_geolocation_hidePostsByGeo"], .hidePostsByGeo-location.template,.location_input.inactive{
                                display: none!important;
                            }
                            .hidePostsByGeo-remove-location{
                                cursor: pointer;
                                color:#a00;
                                box-sizing: inherit;
                                padding: 0;
                                border-radius:50%;
                                width: 17px;
                                height: 17px !important;
                                padding: 0 2px 3px;
                                border: 1px solid #8c8f94;
                                margin-left: 2px;
                                font-size: 19px;
                                font-weight: 600;
                                line-height: 0.3;
                            }
                            .hidePostsByGeo-location .hidePostsByGeo-input,.hidePostsByGeo-titles .hidePostsByGeo-title{
                                width:15%!important;
                            }
                            .hidePostsByGeo-location .hidePostsByGeo-input.categories_input,.hidePostsByGeo-titles .category-input-title{
                                width:20%!important;
                            }
                            .hidePostsByGeo-location .single_rdr_url_input{
                                width:30%!important;
                            }
                            .hidePostsByGeo-location .hidePostsByGeo-input,.hidePostsByGeo-remove-location,.hidePostsByGeo-location .hidePostsByGeo-input.categories_input{
                                height: 28px;
                            }
                            .hidePostsByGeo-location .hidePostsByGeo-input.categories_input:focus{
                                height: auto;
                            }
                        </style>
                        <div class="hidePostsByGeo-excludes">
                            <div class="hidePostsByGeo-titles">
                                <span class="hidePostsByGeo-title category-input-title">Category</span>
                                <span class="hidePostsByGeo-title">Show/Hide</span>
                                <span class="hidePostsByGeo-title">Location</span>
                                <span class="hidePostsByGeo-title"></span>
                                <span style="width:33%!important;" class="hidePostsByGeo-title">Single Post Redirect URL <a href="https://www.if-so.com/faq-items/what-is-the-woocommerce-single-product-redirect-option/?utm_source=Plugin&utm_medium=settings&utm_campaign=woocommerce_product_exclude" target="_blank" class="general-tool-tip ifso_tooltip">?</a></span>
                            </div>
                            <div class="hidePostsByGeo-location template">
                                <select title="Control-click options to select multiple categories" class="hidePostsByGeo-input categories_input">
                                    <?php
                                    foreach(array_values(get_terms(['taxonomy'=>[],'orderby'=>'id'])) as $term){
                                        echo "<option value='{$term->taxonomy}||{$term->term_taxonomy_id}'>{$term->name} (Taxonomy : {$term->taxonomy})</option>";
                                    }
                                    ?>
                                </select>
                                <select class="hidePostsByGeo-input showhide_input">
                                    <option value="hide">Hide In</option>
                                    <option value="show">Show only In</option>
                                </select>
                                <select class="hidePostsByGeo-input location_type_input">
                                    <option value="countryCode">Country</option>
                                    <option value="city">City</option>
                                    <option value="stateProv">State</option>
                                    <option value="continentName">Continent</option>
                                </select>
                                <select class="hidePostsByGeo-input location_input country_select">
                                    <?php
                                    require(IFSO_PLUGIN_BASE_DIR . 'public/models/data-rules/country_opts_variable.php');
                                    foreach($country_opts as $country_opt)
                                        echo "<option value='{$country_opt->value}'>$country_opt->display_value</option>";
                                    ?>
                                </select>
                                <input class="hidePostsByGeo-input location_input inactive" type="text" placeholder="Location Name">
                                <input class="hidePostsByGeo-input single_rdr_url_input" type="text" placeholder="https://example.com">
                                <button class="hidePostsByGeo-remove-location">-</button>
                            </div>
                        </div>
                        <button class="hidePostsByGeo-add-exclude button-secondary" onclick="event.preventDefault();hidePostsByGeoUIInstance.add_exclude();">Add Rule</button>
                    </div>
                </td>
            </tr>
            <script>

                var hidePostsByGeoUI = function(){
                    this.setting_option_field_textarea = document.querySelector('textarea[name="ifso_geolocation_hidePostsByGeo"]');
                    this.exclude_els_wrap = document.querySelector('.hidePostsByGeo-excludes');
                    this.exclude_fields_to_el_classes = {
                        showhide : 'showhide_input',
                        location_type : 'location_type_input',
                        location : 'location_input',
                        categories : 'categories_input',
                        rdr_url : 'single_rdr_url_input'
                    };
                    this.ui_ready = false;
                    this.generate_excludes_ui();
                }
                hidePostsByGeoUI.prototype = {
                    init_events : function(wrap){
                        var _this = this;
                        var els = wrap.querySelectorAll('.hidePostsByGeo-input');
                        wrap.querySelector('.location_type_input').addEventListener('input',this.switch_location_type_cb);
                        els.forEach(function(el){
                            el.addEventListener('input',_this.recalculate_excludes.bind(_this));
                        });
                        wrap.querySelector('.hidePostsByGeo-remove-location').addEventListener('click',(e)=>{e.target.parentElement.remove();_this.recalculate_excludes();_this.display_titles_or_not();});
                    },
                    add_exclude : function (){
                        var copy = document.querySelector('.hidePostsByGeo-location.template').cloneNode(true);
                        copy.classList.remove('template');
                        this.init_events(copy);
                        this.exclude_els_wrap.appendChild(copy);
                        this.display_titles_or_not();
                        return copy;
                    },
                    recalculate_excludes : function (){
                        if(!this.ui_ready) return;
                        var _this = this;
                        var excludes = [];
                        this.get_exclude_elements().forEach(function(el){
                            var excl = {};
                            Object.keys(_this.exclude_fields_to_el_classes).forEach(function(key){
                                var element = el.querySelector('.'+_this.exclude_fields_to_el_classes[key]+':not(.inactive)');
                                excl[key] = element.value;
                            })
                            if(excl.location==='' || excl.categories==='') return;
                            excludes.push(excl);
                        });
                        this.setting_option_field_textarea.value =
                            JSON.stringify(excludes)==='[]' ? '' : JSON.stringify(excludes);
                    },
                    generate_excludes_ui : function(){
                        var _this = this;
                        var option_value_json = this.setting_option_field_textarea.value;
                        if(option_value_json!==''){
                            var option_value = JSON.parse(option_value_json);
                            option_value.forEach(function(exclude){
                                var added_exclude = _this.add_exclude();
                                Object.keys(exclude).forEach(function (field){
                                    var element = added_exclude.querySelector('.'+_this.exclude_fields_to_el_classes[field]+':not(.inactive)');
                                    element.value = exclude[field];
                                    element.dispatchEvent(new Event('input', { 'bubbles': true }))
                                })
                            });
                        }
                        this.display_titles_or_not();
                        this.ui_ready = true;
                    },
                    switch_location_type_cb : function(e){
                        var parent  = e.target.parentElement;
                        parent.querySelector('.location_input.inactive').classList.remove('inactive');
                        if(e.target.value==='countryCode')
                            parent.querySelector('.location_input:not(.country_select)').classList.add('inactive');
                        else
                            parent.querySelector('.country_select').classList.add('inactive');
                    },
                    get_exclude_elements : function(){
                        return this.exclude_els_wrap.querySelectorAll('.hidePostsByGeo-location:not(.template)');
                    },
                    display_titles_or_not : function (){
                        if(this.get_exclude_elements().length===0)
                            this.exclude_els_wrap.querySelector('.hidePostsByGeo-titles').style.display = 'none';
                        else
                            this.exclude_els_wrap.querySelector('.hidePostsByGeo-titles').style.display = '';
                    }
                };
                var hidePostsByGeoUIInstance = new hidePostsByGeoUI();
            </script>
            <?php
        }

        public function register_extra_settings($extra_settings){
            $load_modal_option = function(){
                $default = false;
                $postName = 'ifso_geolocation_logGeoRequests';
                return  new PluginSettingsService\IfSoSettingsYesNoOption($postName  . '_option',$default,$postName);
            };

            $load_block_geo_bots = function(){
                $default = false;
                $postName = 'ifso_geolocation_blockGeoBots';
                return  new PluginSettingsService\IfSoSettingsYesNoOption($postName  . '_option',$default,$postName);
            };

            $browser_geolocation_mode = function(){
                $default = 0;
                $postName = 'ifso_geolocation_browserLocationMode';
                return  new PluginSettingsService\IfSoSettingsNumberOption($postName  . '_option',$default,$postName);
            };

            $hide_posts_by_geo = function(){
                $default='';
                $postName = 'ifso_geolocation_hidePostsByGeo';
                return new PluginSettingsService\IfSoSettingsStringOption($postName.'_option',$default,$postName);
            };

            $extra_settings->geolocation = ['logGeoRequests' => $load_modal_option(),'blockGeoBots'=>$load_block_geo_bots(),
                                            'browserLocationMode'=>$browser_geolocation_mode(),'hidePostsByGeo'=>$hide_posts_by_geo()];

            return $extra_settings;
        }

    }
}