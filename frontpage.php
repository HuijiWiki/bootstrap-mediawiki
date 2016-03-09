<?php

class FrontPage{
 
    static function showPage() {
        require_once('/var/www/html/Confidential.php');
        global $wgUser, $wgParser;
        $templateParser = new TemplateParser(  __DIR__.'/View'  );
        $output = ''; // Prevent E_NOTICE

        //user login
        if ( $wgUser->isLoggedIn() ) {
            $login = true;
        }else{
            $login = false;
            $register = Linker::linkKnown( SpecialPage::getTitleFor('Userlogin'), '注册', array( ),array('type' => 'signup') );
            $active = 'active';
            $inactive = 'in active';
        }
        // check mobile device
        $mobile = mobiledetect();
        $mobileUser = $mobile && $login;
        if (!$mobileUser)
        {    //right data
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
            if (empty($allSiteRank)) {
              $allSiteRank = AllSitesInfo::getAllSitesRankData( '', date('Y-m-d',strtotime('-2 days')) );
            }
            $siteRank = array_slice($allSiteRank,0 ,10);
            $siteInfo = array();
            foreach ($siteRank as $key=>$value) {
                $site = new WikiSite($value['site_prefix']);
                $siteRank[$key]['site_prefix'] = $site->getSiteName();
                $siteRank[$key]['site_url'] = $site->getUrl();
                $stats = $site->getSiteStats();
                $siteRank[$key]['totalEdits'] = $stats['edits'];
                $siteRank[$key]['totalArticles'] = $stats['articles'];
                $siteRank[$key]['totalPages'] = $stats['pages'];
                $siteRank[$key]['totalUsers'] = $stats['users'];
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
            $lange = '<svg width="710" height="110" class=" ">
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
                 </svg>';
                    //url helpManual huijitramac
            $helpManual = 'http://www.huiji.wiki/wiki/%E5%B8%AE%E5%8A%A9:%E7%BC%96%E8%BE%91%E6%89%8B%E5%86%8C';
            $tarmac = 'http://www.huiji.wiki/wiki/%E7%81%B0%E6%9C%BAwiki:%E7%81%B0%E6%9C%BA%E5%81%9C%E6%9C%BA%E5%9D%AA';
            $contact = 'http://www.huiji.wiki/wiki/%E7%81%B0%E6%9C%BAwiki:%E8%81%94%E7%B3%BB%E6%88%91%E4%BB%AC';
        }
        if ($login){
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
            // $recSite = array_slice($allSiteRank,0 ,5);
            $recommendSite = array();
            foreach($allSiteRank as $value){
                $isFollowSite = $usf->checkUserSiteFollow( $wgUser, $value['site_prefix']);
                if($isFollowSite == false ){
                    $fsres['s_name'] = HuijiPrefix::prefixToSiteName($value['site_prefix']);
                    $fsres['s_url'] = HuijiPrefix::prefixToUrl($value['site_prefix']);
                    $fsres['s_prefix'] = $value['site_prefix'];
                    $fsres['s_avatar'] = (new wSiteAvatar($value['site_prefix'], 'l'))->getAvatarHtml();
                    $recommendSite[] = $fsres;
                }
            }
            $recommendSite = array_slice($recommendSite, 0, 5);
        }
        if ($login && !$mobile){
            $infoHeader = wfMessage('info-header-user')->parseAsBlock();
        } elseif (!$login) {
            $infoHeader = wfMessage('info-header-anon')->parseAsBlock();
        } else {
            $infoHeader = '';
        }
        $o = new SaeTOAuthV2( Confidential::$weibo_app_id , Confidential::$weibo_app_secret );
        $weiboUrl = $o->getAuthorizeURL( Confidential::$weibo_callback_url );
        $qqUrl = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=101264508&state=huijistate&redirect_uri=http%3a%2f%2fwww.huiji.wiki%2fwiki%2fspecial%3acallbackqq";
        $output .= $templateParser->processTemplate(
            'frontpage',
            array(
                'mobileUser' => $mobileUser,
                'infoHeader' => $infoHeader,
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
                'recommendSite' => $recommendSite,
                'recommendRes'=>$recommendRes,
                'recContent' => $recContent,
                'followUserCount' => $followUserCount,
                'followSiteCount' => $followSiteCount,
                'helpManual' => $helpManual,
                'tarmac' => $tarmac,
                'contact' => $contact,
                'weiboUrl' => $weiboUrl,
                'qqUrl' => $qqUrl,
                )
        );
        return $output;
    }
}
?>
