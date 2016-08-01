<?php   
/**
* CommonStyle
*/
class SpecialCommonStyle extends UnlistedSpecialPage{
    
    function __construct(){
        parent::__construct( 'CommonStyle', 'editinterface' );
    }

    public function execute( $params ) {
        global $wgHuijiPrefix, $wgUser;
        $templateParser = new TemplateParser(  __DIR__ . '/pages' );
        $cssPath = "/var/www/virtual/".$wgHuijiPrefix."skins/bootstrap-mediawik/css";
        $this->setHeaders();
        $out = $this->getOutput();
        $output = '';
        // $output = "<div id='color-container' class='darken'></div>";
        if (class_exists('VoteStar')){
            $out->addModules( 'ext.VoteNY.styles' );
        }
        $out->addModulestyles('socialprofile.commonstyle.css');
        $out->addModules( 'ext.socialprofile.commonstyle.js' );
        // $out->addHtml($output);
        return true;
        $cssCon_1 = CommonStyle::getStyle();
        if ($cssCon_1 == false) {
            $isNew = 0;
        }else{
            $isNew = 1;
        }
        if ( $cssCon_1['cssContent'] == null ) {
            $lessCon = array();
            $show = 'none';
        }else{
            $lessCon = (array)json_decode( $cssCon_1['cssContent'] );
            $show = '';
        }
        $hArr1 = array(
                'label'=>'标题',
                'detail'=>array(
                    '@detail-h1'=>'h1',
                    '@detail-h2'=>'h2',
                    '@detail-h3'=>'h3',
                    '@detail-h4'=>'h4',
                    '@detail-h5'=>'h5',
                )
        );
        $hArr2 = array(
                'label'=>'导航',
                'detail'=>array(
                    '@detail-sub-bg'=>'背景',
                    '@detail-sub-a'=>'文字',
                    '@detail-sub-a-hover-bg'=>'悬浮',
                    '@detail-sub-site-count'=>'统计数字',
                )
        );
        $hArr3 = array(
                'label'=>'页面底部',
                'detail'=>array(
                    '@detail-bottom-bg'=>'背景',
                    '@detail-bottom-color'=>'文字',

                )
        );
        $hArr4 = array(
                'label'=>'quote',
                'detail'=>array(
                    '@detail-quote-bg'=>'背景',
                    '@detail-quote-color'=>'文字',
                    '@detail-quote-a'=>'链接',
                    '@detail-quote-border'=>'边框',
                )
        );
        $hArr5 = array(
                'label'=>'wikitable',
                'detail'=>array(
                    '@detail-wikitable-bg'=>'背景',
                    '@detail-wikitable-color'=>'文字',
                    '@detail-wikitable-a'=>'链接',
                    '@detail-wikitable-border'=>'边框',
                    '@detail-wikitable-th-bg'=>'th背景',
                )
        );
        $hArr6 = array(
                'label'=>'infobox整体',
                'detail'=>array(
                    '@detail-infobox-bg'=>'背景',
                    '@detail-infobox-color'=>'文字',
                    '@detail-infobox-a'=>'链接',
                    '@detail-infobox-border'=>'边框',
                )
        );
        $hArr7 = array(
                'label'=>'infobox title',
                'detail'=>array(
                    '@detail-infobox-title-bg'=>'背景',
                    '@detail-infobox-title-color'=>'文字',
                )
        );
        $hArr8 = array(
                'label'=>'infobox header',
                'detail'=>array(
                    '@detail-infobox-item-title-bg'=>'背景',
                    '@detail-infobox-item-title-color'=>'文字',
                )
        );
        $hArr9 = array(
                'label'=>'infobox label',
                'detail'=>array(
                    '@detail-infobox-item-label-bg'=>'背景',
                    '@detail-infobox-item-label-color'=>'文字',
                    '@detail-infobox-item-label-a'=>'链接',
                    '@detail-infobox-item-label-border'=>'边框',
                )
        );
        $hArr10 = array(
                'label'=>'infobox data',
                'detail'=>array(
                    '@detail-infobox-item-detail-bg'=>'背景',
                    '@detail-infobox-item-detail-color'=>'文字',
                    '@detail-infobox-item-detail-a'=>'链接',
                    '@detail-infobox-item-detail-border'=>'边框',
                )
        );
        $hArr11 = array(
                'label'=>'navbox 整体',
                'detail'=>array(
                    '@detail-navbox-bg'=>'背景',
                    '@detail-navbox-color'=>'文字',
                    '@detail-navbox-a'=>'链接',
                )
        );
        $hArr12 = array(
                'label'=>'navbox title',
                'detail'=>array(
                    '@detail-navbox-title-bg'=>'背景',
                    '@detail-navbox-title-color'=>'文字',
                    '@detail-navbox-title-a'=>'链接',
                )
        );
        $hArr13 = array(
                'label'=>'navbox group',
                'detail'=>array(
                    '@detail-navbox-group-bg'=>'背景',
                    '@detail-navbox-group-color'=>'文字',
                    '@detail-navbox-group-a'=>'链接',
                )
        );
        $hArr14 = array(
                'label'=>'navbox list',
                'detail'=>array(
                    '@detail-navbox-list-bg'=>'背景',
                    '@detail-navbox-list-color'=>'文字',
                    '@detail-navbox-list-a'=>'链接',
                    '@detail-navbox-list-new'=>'无效链接',
                    '@detail-navbox-list-odd-bg'=>'奇数背景',
                    '@detail-navbox-list-even-bg'=>'偶数背景',
                )
        );
        $hArr15 = array(
                'label'=>'navbox abovebelow',
                'detail'=>array(
                    '@detail-navbox-abovebelow-bg'=>'背景',
                    '@detail-navbox-abovebelow-color'=>'文字',
                    '@detail-navbox-abovebelow-a'=>'链接',
                )
        );
        $hArr16 = array(
                'label'=>'投票',
                    'detail'=>array(
                        '@detail-vote-color'=>'文字',
                        '@detail-vote-score-bg'=>'分数背景',
                        '@detail-vote-score-color'=>'分数字体',
                        '@detail-vote-star'=>'星星颜色',
                        '@detail-vote-active-star'=>'选中颜色',
                    )
        );
        $valueName = array(
                        '@detail-bg' => 'wiki-outer-body背景',
                        '@detail-inner-bg' => 'wiki-body背景',
                        '@detail-color' => '文字',
                        '@detail-secondary' => '文字辅色',
                        '@detail-contentsub' => '副标题文字',
                        '@detail-a' => '有效链接',
                        '@detail-new' => '无效链接',
                        '@detail-border' => '边框',
                        '@detail-toc-a' => '目录链接',
                        '@detail-toc-a-hover' => '目录链接-hover',
                        'h1' => $hArr1,
                        'h2' => $hArr2,
                        'h3' => $hArr3,
                        'h4' => $hArr4,
                        'h5' => $hArr5,
                        'h6' => $hArr6,
                        'h7' => $hArr7,
                        'h8' => $hArr8,
                        'h9' => $hArr9,
                        'h10' => $hArr10,
                        'h11' => $hArr11,
                        'h12' => $hArr12,
                        'h13' => $hArr13,
                        'h14' => $hArr14,
                        'h15' => $hArr15,
                        'h16' => $hArr16,
                    );
        $styleArr = array();
        foreach ($valueName as $key => $value) {
            if (is_array($value)) {
                $res1 = array();
                $res1['ish'] = true;
                $res1['label'] = $value['label'];
                foreach ($value['detail'] as $key1 => $value1) {
                    $res1['h'][] = array(
                                'name2' => $value1,

                                'variable2' => $key1,
                                'value2' => !isset( $lessCon[$key1] ) ? 'false' : $lessCon[$key1],
                            );
                }
                $styleArr[] = $res1;
            }else{
                $styleArr[] = array(
                                'ish' => false,
                                'name' => $value,
                                'variable' => $key,
                                'value' => !isset( $lessCon[$key] ) ? 'false' : $lessCon[$key],
                            ); 
            }
            
        }
        // print_r($styleArr);die();
        $mainBase = !isset( $lessCon['@main-base'] ) ? "#333" : $lessCon['@main-base'];
        $bg = !isset( $lessCon['@bg'] ) ? "#fff" : $lessCon['@bg'];
        $bgInner = !isset( $lessCon['@bg-inner'] ) ? "#fff" : $lessCon['@bg-inner'];
        $a = !isset( $lessCon['@a'] ) ? "#428bca" : $lessCon['@a'];
        $subBg = !isset( $lessCon['@sub-bg'] ) ? "#f6f8f8" : $lessCon['@sub-bg'];
        $subA = !isset( $lessCon['@sub-a'] ) ? "#333" : $lessCon['@sub-a'];
        $modal = !isset( $lessCon['@modal'] ) ? "#222" : $lessCon['@modal'];
        $default = !isset( $lessCon['@brand-default'] ) ? "#ffffff" : $lessCon['@brand-default'];
        $primary = !isset( $lessCon['@brand-primary'] ) ? "#337ab7" : $lessCon['@brand-primary'];
        $success = !isset( $lessCon['@brand-success'] ) ? "#5cb85c" : $lessCon['@brand-success'];
        $info = !isset( $lessCon['@brand-info'] ) ? "#5bc0de" : $lessCon['@brand-info'];
        $warning = !isset( $lessCon['@brand-warning'] ) ? "#f0ad4e" : $lessCon['@brand-warning'];
        $danger = !isset( $lessCon['@brand-danger'] ) ? "#d9534f" : $lessCon['@brand-danger'];
        $well = !isset( $lessCon['@well'] ) ? "#f5f5f5" : $lessCon['@well'];
        $output .= $templateParser->processTemplate(
                            'view',
                            array(
                                'mainBase' => $mainBase,
                                'bg' => $bg,
                                'bgInner' => $bgInner,
                                'a' => $a,
                                'subBg' => $subBg,
                                'subA' => $subA,
                                'modal' => $modal,
                                'default' => $default,
                                'primary' => $primary,
                                'success' => $success,
                                'info' => $info,
                                'warning' => $warning,
                                'danger' => $danger,
                                'well' => $well,
                                'styleArr' => $styleArr,
                                'isNew' => $isNew,
                            )
                    );
        $out->addHTML( $output );
        $out->addModulestyles('socialprofile.commonstyle.css');
        $out->addModules( 'ext.socialprofile.commonstyle.js' );
    }

}

?>
