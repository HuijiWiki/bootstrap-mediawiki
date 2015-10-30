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
            <div class="modal alert-return">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <p></p>
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

            <div class="modal fade user-login" tabindex="-1" role="dialog" aria-labelledby="userLoginModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="ModalLabel">登录</h4>
                        </div>
                        <div class="modal-body">
                            <div class="mw-ui-container login-wrap">
                                <div class="userloginprompt"></div>
                                <div class="userloginForm">
                                    <form name="userlogin" class="mw-ui-vform" method="post">
                                        <section class="mw-form-header">
                                        </section>

                                        <div class="mw-ui-vform-field user-name">
                                            <label for="wpName1">
                                                用户名
                                            </label>
                                            <input id="login-user-name" class="loginText mw-ui-input form-control" type="text"  tabindex="1" size="20" placeholder="请输入你的用户名" name="wpName">
                                        </div>

                                        <div class="mw-ui-vform-field">
                                            <label for="wpPassword1">
                                                密码 <a href="/wiki/%E7%89%B9%E6%AE%8A:%E9%87%8D%E8%AE%BE%E5%AF%86%E7%A0%81" title="特殊:重设密码" class="mw-ui-flush-right">忘记密码？</a>
                                            </label>
                                            <input id="login-user-password" class="loginPassword mw-ui-input form-control"  tabindex="2" size="20" autofocus="" placeholder="请输入你的密码" type="password" name="wpPassword">
                                        </div>



                                        <div class="mw-ui-vform-field">
                                            <div class="">
                                                <input name="wpRemember" type="checkbox" value="1" id="wpRemember" tabindex="4" style="margin-right: 5px;"><label for="wpRemember">
                                                    记住我的登录状态</label>
                                            </div>
                                            <div>
                                                <span id="qqLoginBtn"></span>
                                            </div>
                                        </div>

                                        <div class="mw-ui-vform-field">
                                            <input id="wpLoginAttempt" tabindex="6" class="mw-ui-button  mw-ui-block mw-ui-constructive" type="button" value="登录" name="wpLoginAttempt">
                                        </div>

    <!--                                     <div class="mw-ui-vform-field" id="mw-userlogin-help">
                                            <a href="https://www.mediawiki.org/wiki/Special:MyLanguage/Help:Logging_in">登录帮助</a>
                                        </div> -->

                                        <div id="mw-createaccount-cta">
                                            没有账户？<?php echo Linker::linkKnown( SpecialPage::getTitleFor('Userlogin'), '注册', array('id' => 'pt-createaccount' ),array('type' => 'signup') ); ?>
                                        </div>
                                        <input type="hidden" name="wpLoginToken" value="5b59d95be44d1173971ec0b44d9fffa4">
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

<!--            <div class="alert-wrap">-->
<!--                <div class="alert" role="alert">good</div>-->
<!--            </div>-->

       
