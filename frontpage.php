<?php

class FrontPage{
        
    static function showPage() {
        global $wgUser;
        $templateParser = new TemplateParser(  __DIR__  );
        $output = ''; // Prevent E_NOTICE
        //right data
        $fileCount = AllSitesInfo::getAllUploadFileCount();
        $siteCount = AllSitesInfo::getSiteCountNum();
        $userCount = AllSitesInfo::getUsreCountNum();
        $editCount = AllSitesInfo::getAllSiteEditCount();
        $pageCount = AllSitesInfo::getAllPageCount();
        $userName = $wgUser->getName();
        $usreId = $wgUser->getId();
        $avatar = new wAvatar( $usreId, 'l' );
        $userAvatar = $avatar->getAvatarURL();
        //level
        $stats = new UserStats( $usreId, $userName );
        $stats_data = $stats->getUserStats();
        $user_level = new UserLevel( $stats_data['points'] );
        $level_link = Title::makeTitle( NS_HELP, wfMessage( 'user-profile-userlevels-link' )->inContentLanguage()->text() );
        $levelUrl = htmlspecialchars( $level_link->getFullURL() );
        $userLevel = $user_level->getLevelName();
        //user info
        $notice = SpecialPage::getTitleFor('ViewFollows');
        $contributions = SpecialPage::getTitleFor('Contributions');
        $userEdit = Linker::link( $contributions, $stats_data['edits'], array(), array( 'target' => $userName,'contribs' => 'user' ) );
        $follower = Linker::link( $notice, UserUserFollow::getFollowingCount($wgUser), array( 'id' => 'user-following-count' ), array( 'user' => $userName,'rel_type'=>1 ) );
        $followee = Linker::link( $notice, UserUserFollow::getFollowerCount($wgUser), array( 'id' => 'user-follower-count' ), array( 'user' => $userName,'rel_type' => 2 ) );
        //siterank
        $yesterday = date('Y-m-d',strtotime('-1 days'));
        $allSiteRank = AllSitesInfo::getAllSitesRankData( '', $yesterday );
        $siteRank = array_slice($allSiteRank,0 ,10);
        $siteInfo = array();
        foreach ($siteRank as $key=>$value) {
            $siteRank[$key]['site_prefix'] = HuijiPrefix::prefixToSiteName($value['site_prefix']);
            $siteRank[$key]['site_url'] = HuijiPrefix::prefixToUrl($value['site_prefix']);
            $siteInfo = AllSitesInfo::getPageInfoByPrefix($value['site_prefix']);
            $siteRank[$key]['totalEdits'] = $siteInfo['totalEdits'];
            $siteRank[$key]['totalArticles'] = $siteInfo['totalArticles'];
            $siteRank[$key]['totalPages'] = $siteInfo['totalPages'];
            $siteRank[$key]['totalUsers'] = $siteInfo['totalUsers'];
        }
        //userrank
        $weekRank = UserStats::getUserRank(10,'week');
        $monthRank = UserStats::getUserRank(20,'month');
        $totalRank = UserStats::getUserRank(20,'total');

        //小蓝格
        $ueb = new UserEditBox();
        $editBox = $editData = array();
        $userEditInfo = $ueb->getUserEditInfo($usreId);
        $maxlen = $currentMaxlen = 0; //init variables.
        foreach ($userEditInfo as $value) {
            if (is_object($value) && !empty($value->_id) && $value->value > 0) {
                $editBox[$value->_id] = $value->value;
                $editData[] = $value->_id;
            }
            
        }
        $today = date("Y-m-d");
        $yesterday = date("Y-m-d",strtotime("-1 day"));
        $editBox[$today] = UserEditBox::getTodayEdit($usreId);
        if (!empty($editBox[$today])) {
            $editData[] = $today;
        }
        $totalEdit = count($editData);
        if ($totalEdit > 0){
            $resArr[] = strtotime($editData[0]);
            $maxlen = 1;                
        }

        for($k=1;$k<count($editData);$k++){
            if(in_array(strtotime($editData[$k])-86400, $resArr)){
                $resArr[] = strtotime($editData[$k]);
                if(count($resArr) > $maxlen){
                    $maxlen = count($resArr);
                }
            }else{
                $resArr = array();
                $resArr[] = strtotime($editData[$k]);
            }
            if( $resArr[count($resArr)-1] == strtotime($today) || $resArr[count($resArr)-1] == strtotime($yesterday) ){
                $currentMaxlen = count($resArr);
            }else{
                $currentMaxlen = 0;
            }
        }
        $lange = '<svg width="725" height="110" class=" ">
                    <g transform="translate(20, 20)">';
        $n = 676/13;
        $dateArr = array();
        for($k=0;$k<365;$k++){
            $dateArr[]= date('Y-m-d',strtotime("-$k day"));
        }
        $desdateArr = array_reverse($dateArr);
        $translate = array();
        for($i=0;$i<=$n;$i++){
            $trani = $i*13;
            $lange .= '<g transform="translate('.$trani.', 0)">';
                    $dayofweek = date('w', strtotime($desdateArr[0]));
                    if($i == 0){
                        $j = $dayofweek;
                        $start = 0;
                        $m = 7-$dayofweek;
                    }else{
                        $j=0;
                        $m = 7;
                        $start = $i*7-$dayofweek;
                    }
                    
                    $zoneDate = array_slice($desdateArr, $start, $m);
                    foreach ($zoneDate as $val) {
                        $arrDate[$j] = $val;
                        $y = $j*13;
                        $dataCount = (isset($editBox[$val]))?$editBox[$val]:0;
                        if ($dataCount == 0) {
                                $color = '#eee';
                            }elseif ($dataCount > 0 && $dataCount <= 8 ) {
                                $color = '#86beee';
                            }elseif ($dataCount > 8 && $dataCount <= 21 ){
                                $color = '#5ea2de';
                            }elseif ($dataCount > 21 && $dataCount <= 55 ){
                                $color = '#256fb1';
                            }else {
                                $color = '#0d5493';
                            }

                        $lange .= '<rect class="day" width="11" height="11" y="'.$y.'" fill="'.$color.'" data-count="'.$dataCount.'" data-date="'.$val.'" title="'.$val.' 编辑'.$dataCount.'次"></rect>';
                        $j=($j>=7)?0:($j+1);
                    }
                    if (!empty($arrDate[0])){
                        $translate[$arrDate[0]] = $trani;
                    }
            $lange .= '</g>';
            }
        $moninit = 1;
        for ($p=0; $p < 12; $p++) {
            $year = date('Y')-1;
            $mon = date('m')+$p+1;
            if($mon > 12){
                $mon=$moninit++;
                $year = date('Y');
            }
            $sunDay = UserEditBox::getSunday($mon,$year);
            $Stime = strtotime($sunDay);
            $sunDay = date('Y-m-d',$Stime);
            if (!isset($translate[$sunDay])) {
                $Suntime = strtotime($sunDay); 
                $Sundate = date('Y',$Suntime)-1;
                $sunDay = UserEditBox::getSunday($mon,$Sundate);
            }
            foreach ($translate as $key => $value) {
                if ( strtotime($key) == strtotime($sunDay)) {
                    $x = $value;
                }
            }
            $lange .= '<text x="'.$x.'" y="-5" class="'.$year.'">'.$mon.'月</text>';
        }
        $lange .= ' <text text-anchor="middle" class="wday" dx="-10" dy="9" style="display: none;">S</text>
                    <text text-anchor="middle" class="wday" dx="-10" dy="22">M</text>
                    <text text-anchor="middle" class="wday" dx="-10" dy="35" style="display: none;">T</text>
                    <text text-anchor="middle" class="wday" dx="-10" dy="48">W</text>
                    <text text-anchor="middle" class="wday" dx="-10" dy="61" style="display: none;">T</text>
                    <text text-anchor="middle" class="wday" dx="-10" dy="74">F</text>
                    <text text-anchor="middle" class="wday" dx="-10" dy="87" style="display: none;">S</text>
                  </g>
                </svg>
                <div class="edit-statistics"><p>连续编辑纪录<span>'.$maxlen.'</span></p><p>总编辑天数<span>'.$totalEdit.'</span></p><p>当前连续编辑<span>'.$currentMaxlen.'<span></p></div>';
        //user login
        if ( $wgUser->isLoggedIn() ) {
            $login = true;
        }else{
            $login = false;
            $register = Linker::linkKnown( SpecialPage::getTitleFor('Userlogin'), '注册', array('id' => 'pt-createaccount' ),array('type' => 'signup') );
            $active = 'active';
            $inactive = 'in active';
        }

        // follow
        $followUserCount = UserUserFollow::getFollowingCount($wgUser);
        if ( $followUserCount >= 5 ) {
            $userHidden = true;
        }else{
            $userHidden = false;
        }
        $followSiteCount = UserSiteFollow::getFollowingCount($wgUser);
        if ( $followSiteCount >= 5 ) {
            $siteHidden = true;
        }else{
            $siteHidden = false;
        }

        //recommend user $weekRank $monthRank  $totalRank
        $uuf = new UserUserFollow();
        if ( count($weekRank) >=8 ) {
            $recommend = UserStats::getUserRank(10,'week');
        }elseif ( count($monthRank) >=8 ) {
            $recommend = UserStats::getUserRank(20,'month');
        }else{
            $recommend = UserStats::getUserRank(20,'total');
        }
        $recommendRes = array();
        $flres = array();
        foreach ($recommend as $value) {
            $tuser = User::newFromName($value['user_name']);
            $isFollow = $uuf->checkUserUserFollow( $wgUser, $tuser );
            if(!$isFollow && $value['user_name'] != $userName){
                $flres['avatar'] = $value['avatarImage'];
                $flres['username'] = $value['user_name'];
                $flres['userurl'] = $value['user_url'];
                $recommendRes[] = $flres;
            }         
        }
        $recommendRes = array_slice($recommendRes, 0, 5);
        //recommend site
        $usf = new UserSiteFollow();
        $recSite = array_slice($allSiteRank,0 ,10);
        $recommendSite = array();
        foreach($recSite as $value){
            $isFollowSite = $usf->checkUserSiteFollow( $wgUser, $value['site_prefix']);
            if($isFollowSite == false ){
                $fsres['s_name'] = HuijiPrefix::prefixToSiteName($value['site_prefix']);
                $fsres['s_url'] = HuijiPrefix::prefixToUrl($value['site_prefix']);
                $fsres['s_prefix'] = $value['site_prefix'];
                $fsres['s_avatar'] = (new wSiteAvatar($value['site_prefix'], 'l'))->getAvatarHtml();
                $recommendSite[] = $fsres;
            }
        }
        //recommend content
        $recRes = new BootstrapMediaWikiTemplate();
        $block = $recRes->getIndexBlock( '首页/Admin' );
        $n = count($block);
        $recContent = array();
        for ($i=0; $i < $n; $i++) {
            $contentRes['title'] = $block[$i]->title;
            $contentRes['wikiname'] = $block[$i]->wikiname;
            $contentRes['desc'] = $block[$i]->desc;
            $contentRes['wikiurl'] = $block[$i]->wikiurl;
            $contentRes['siteurl'] = $block[$i]->siteurl;
            $contentRes['backgroungimg'] = $block[$i]->backgroungimg;
            $recContent[] = $contentRes;
        }

        //url helpManual huijitramac
        $helpManual = 'http://www.huiji.wiki/wiki/%E5%B8%AE%E5%8A%A9:%E7%BC%96%E8%BE%91%E6%89%8B%E5%86%8C';
        $tarmac = 'http://www.huiji.wiki/wiki/%E7%81%B0%E6%9C%BAwiki:%E7%81%B0%E6%9C%BA%E5%81%9C%E6%9C%BA%E5%9D%AA';
        $contact = 'http://www.huiji.wiki/wiki/%E7%81%B0%E6%9C%BAwiki:%E8%81%94%E7%B3%BB%E6%88%91%E4%BB%AC';

        $output .= $templateParser->processTemplate(
            'frontpage',
            array(
                'fileCount' => $fileCount,
                'siteCount' => $siteCount,
                'userCount' => $userCount,
                'editCount' => $editCount,
                'pageCount' => $pageCount,
                'userName' => $userName,
                'userAvatar' => $userAvatar,
                'levelUrl' => $levelUrl,
                'userLevel' => $userLevel,
                'userEdit' => $userEdit,
                'follower' => $follower,
                'followee' => $followee,
                'siteRank' => $siteRank,
                'weekRank' => $weekRank,
                'monthRank' => $monthRank,
                'totalRank' => $totalRank,
                'lange' => $lange,
                'login' => $login,
                'register' => $register,
                'userHidden' => $userHidden,
                'siteHidden' => $siteHidden,
                'active' => $active,
                'inactive' => $inactive,
                'recommendRes' => $recommendRes,
                'recommendSite' => $recommendSite,
                'recContent' => $recContent,
                'followUserCount' => $followUserCount,
                'followSiteCount' => $followSiteCount,
                'helpManual' => $helpManual,
                'tarmac' => $tarmac,
                'contact' => $contact,
                )
        );
        return $output;
    }
}
?>
