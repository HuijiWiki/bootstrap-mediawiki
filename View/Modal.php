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
            <div class="modal fade qqlogin tabindex="-1" role="dialog" aria-labelledby="mySmModalLabel" aria-hidden="true">
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

            <div class="modal fade user-login" tabindex="-1" role="dialog" aria-labelledby="userLoginModalLabel" aria-hidden="true">
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
                                        <div class="mw-ui-vform-field">
                                            <input id="login-user-name" class="loginText" type="text" placeholder="请输入你的用户名">
                                        </div>
                                        <div class="mw-ui-vform-field">
                                            <input id="login-user-password" class="loginPassword"  placeholder="请输入你的密码" type="password" name="wpPassword">
                                        </div>
                                        <div class="mw-ui-vform-field">
                                                <input name="wpRemember" type="checkbox" value="1" id="wpRemember" tabindex="4" ><label for="wpRemember">记住我</label>
                                                <a href="/wiki/%E7%89%B9%E6%AE%8A:%E9%87%8D%E8%AE%BE%E5%AF%86%E7%A0%81" title="特殊:重设密码" class="mw-ui-flush-right">&nbsp;&nbsp;忘记密码？</a>
                                                <a href="/wiki/%E7%89%B9%E6%AE%8A:%E9%87%8D%E8%AE%BE%E5%AF%86%E7%A0%81" title="特殊:用户登录" class="mw-ui-flush-right">临时密码登录</a>
                                        </div>
                                        <div class="mw-ui-vform-field">
                                            <input id="wpLoginAttempt" tabindex="6" class="mw-ui-button btn mw-ui-block mw-ui-constructive" data-loading-text="登录中..." type="button" value="登 录" name="wpLoginAttempt">
                                        </div>
                                        <div class="mw-ui-vform-field api-login">
                                            <?php echo Linker::linkKnown( SpecialPage::getTitleFor('Userlogin'), '注册账户', array('id' => 'pt-createaccount' ),array('type' => 'signup') ); ?>
                                            <div>
                                            <span>联合登录</span>
                                            <a href="https://api.weibo.com/oauth2/authorize?client_id=2445834038&redirect_uri=http%3A%2F%2Fhuijiwiki.com%2Fwiki%2Fspecial%3Acallbackweibo&response_type=code" class="icon-weibo-share"></a>
                                            <a href="https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=101264508&redirect_uri=http://www.huiji.wiki/wiki/special:callbackqq" class="icon-qq-share"></a>
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

       
