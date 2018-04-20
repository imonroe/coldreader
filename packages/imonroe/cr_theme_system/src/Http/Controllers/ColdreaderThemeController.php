<?php

namespace imonroe\cr_theme_system\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\User;
use imonroe\crps\Http\Controllers\UserPreferencesController;

class ColdreaderThemeController{

    protected $theme;
    protected $user_theme;

    public function __construct(){
        $this->theme = $this->base_theme();
    }

    public function selected_theme(){
        $pref = new UserPreferencesController;
        return $pref->check_preference('cr_theme');
    }

    public function set_theme(){
        switch( $this->selected_theme() ){
            case 'default_light':
                $this->theme = $this->base_theme();
                break;
            case 'default_dark':
                $this->theme = $this->theme_default_dark();
                break;
        }
    }
    
    public function get_css(Request $request){
        $this->set_theme();
        return response()
            ->view('cr_theme_system::coldreader_theme', $this->theme, 200)
            ->header('Content-Type', 'text/css')
            ->header('charset', 'UTF-8');

    }

    public function get_theme_json(Request $request){
        $this->set_theme();
        return response()->json($this->theme);
    }

    public function base_theme(){
        return [
            'background_color' => '#fff',
            'primary_text_size' => '1.5em',
            'primary_font_color' => '#000',
            'primary_link_color' => '',
            'navbar_background_color' => '#fff !important',
            'panel_borders' => '1px solid #ccc',
            'panel_header_background' => '#eee',
            'panel_body_background' =>'#FFF',
            'body_font_url' => '',
            'body_font_string' => "sans-serif",
            'jquery_ui_stylesheet' => 'https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css',
            'footer_background_color' => '#ccc',
            'footer_text_color' => '#222',
            'footer_link_color' => '#222',
            'input_background_color' => '#fff',
            'border_glow' => '',
        ];
    }

    public function theme_default_dark(){
        return [
            'background_color' => '#444',
            'primary_text_size' => '1.5em',
            'primary_font_color' => '#8BFBFF',
            'primary_link_color' => '#63FF8F',
            'navbar_background_color' => '#343a40 !important',
            'panel_borders' => '1px solid #A5FFFF',
            'panel_header_background' => '#353535',
            'panel_body_background' =>'#444',
            'body_font_url' => 'https://fonts.googleapis.com/css?family=Abel',
            'body_font_string' => "'Abel', sans-serif",
            'jquery_ui_stylesheet' => 'https://code.jquery.com/ui/1.12.1/themes/dark-hive/jquery-ui.css',
            'footer_background_color' => '#666',
            'footer_text_color' => '#fff',
            'footer_link_color' => '#fff',
            'input_background_color' => '#000',
            'border_glow' => '-webkit-box-shadow: 0px 0px 5px 2px rgba(165, 255, 255, .3);
                            -moz-box-shadow: 0px 0px 5px 2px rgba(165, 255, 255, .3);
                            box-shadow: 0px 0px 5px 2px rgba(165, 255, 255, .3);',
            
        ];
    }

}