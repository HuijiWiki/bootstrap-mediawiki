<?php 
    if ($wgRequest->getText( 'action' ) == 'edit'){
?>
            <!-- Autosave Modal -->
            <div class="modal fade auto-restore" id="autoRestoreModal" tabindex="-1" role="dialog" aria-labelledby="autoRestoreModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="autoRestoreLabel">发现虫洞</h4>
                  </div>
                  <div class="modal-body">
                    <p>灰机发现您在该页面有未保存的更改，是否现在恢复？</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">不恢复</button>
                    <button type="button" class="btn btn-primary">恢复</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- upload -->
            <div class="modal fade video-upload-modal" tabindex="-1" role="dialog" aria-labelledby="mySmModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                            <h4 class="modal-title" id="video-upload-modal-title">添加媒体文件&nbsp;<small>支持youku、bilibili、网易云音乐</small></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                               <label for="video-upload-modal-url" class="control-label">媒体文件连接</label>
                               <textarea class="form-control" id="video-upload-modal-url"></textarea>
                               <label for="video-upload-modal-name" class="control-label">媒体文件名称</label>
                               <input class="form-control" type="text" id="video-upload-modal-name">
                           </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary video-upload-modal-btn" data-loading-text="上传中..." disabled>添加</button>
                        </div>
                    </div>
                </div>
            </div>
<?php 
    }
?>

        <!-- followed list -->
            <div class="modal fade follow-msg" tabindex="-1" role="dialog" aria-labelledby="mySmModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="gridSystemModalLabel">关注了本站的用户</h4>
                        </div>
                        <div class="modal-body">
                           <ul class="follow-modal">
                           </ul>
                        </div>
                    </div>
                </div>
            </div>
        <!-- qqlogin -->
            <div class="modal fade qqlogin" tabindex="-1" role="dialog" aria-labelledby="mySmModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                            <h4 class="modal-title" id="gridSystemModalLabel">您需要完善资料</h4>
                        </div>
                        <div class="modal-body">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade user-login huiji-login" tabindex="-1" role="dialog" aria-labelledby="userLoginModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h5 class="modal-title" id="ModalLabel">登录</h5>
                        </div>
                        <div class="modal-body">
                            <div class="mw-ui-container login-wrap">
                                <div class="userloginForm">
                                    <form name="userlogin" method="post">
                                        <input id="login-user-name" class="huiji-login-text" type="text" placeholder="请输入你的用户名">
                                        <input id="login-user-password" class="huiji-login-text"  placeholder="请输入你的密码" type="password" name="wpPassword">
                                        <input name="wpRemember" type="checkbox" value="1" id="wpRemember" tabindex="4" ><label for="wpRemember">记住我</label>
                                        <a href="/wiki/%E7%89%B9%E6%AE%8A:%E9%87%8D%E8%AE%BE%E5%AF%86%E7%A0%81" title="特殊:重设密码" class="mw-ui-flush-right">&nbsp;&nbsp;忘记密码？</a>
                                        <a href="/wiki/Special:Login" title="特殊:用户登录" class="mw-ui-flush-right">临时密码登录</a>
                                        <input id="wpLoginAttempt" tabindex="6" class="mw-ui-button btn mw-ui-block mw-ui-constructive huiji-login-btn" data-loading-text="登录中..." type="button" value="登 录" name="wpLoginAttempt">
                                        <div class="api-login">
                                            <?php echo Linker::linkKnown( SpecialPage::getTitleFor('Userlogin'), '注册账户', array('id' => 'pt-createaccount' ),array('type' => 'signup') ); ?>
                                            <div>
                                            <span>联合登录</span>
                                            <a href="#" class="icon-weibo-share"></a>
                                            <?php
                                                global $wgHuijiPrefix;
                                                echo '<a href="https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=101264508&state='.$wgHuijiPrefix.'&redirect_uri=http%3a%2f%2fwww.huiji.wiki%2fwiki%2fspecial%3acallbackqq" class="icon-qq-share"></a>';
                                            ?>
                                            <!-- <a href="https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=101264508&state=huijistate&redirect_uri=http%3a%2f%2fwww.huiji.wiki%2fwiki%2fspecial%3acallbackqq" class="icon-qq-share"></a> -->
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
    <!--                    <div class="modal-footer">-->
    <!--                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
    <!--                    </div>-->
                    </div>
                </div>
            </div>



    <!-- uploadcaption -->
    <div class="trans-modal-wrap" id="caption-wrap">
        <div id="caption-upload" class="trans-modal">
            <span class="trans-modal-close">×</span>
            <h3 class="trans-modal-title">创建翻译项目</h3>
            <p class="trans-modal-introduce">翻译项目可以包含多个可排序的文档（例如一篇文章的多个章节），还可以引用翻译组的公共资源。翻译完成后，您可以在TRANS发布翻译作品。</p>
            <form class="trans-modal-form">
                <input type="text" id="caption-id" placeholder="请输入项目id，不可为中文">
                <textarea id="caption-des" placeholder="请输入项目描述"></textarea>
                <input type="file" id="caption-file">

                <div class="btn btn-default trans-modal-submit caption-submit">创建翻译项目</div>
            </form>
        </div>
    </div>


       
