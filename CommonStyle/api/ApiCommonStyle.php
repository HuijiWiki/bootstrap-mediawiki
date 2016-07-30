<?php
class ApiCommonStyle extends ApiBase {
    protected $params;
    public static function getStyleTitle(){
        return Title::MakeTitle( NS_MEDIAWIKI, 'CommonStyle' );
    }

    public function execute() {
        $this->params = $this->extractRequestParams();
        $result = $this->getResult();
        if ($this->params['task'] == 'save'){
             if ( !isset($this->params['content']) ){
                $result->addValue($this->getModuleName(), 'res', ResponseGenerator::getArr(ResponseGenerator::ERROR_MISSING_ARG));
                return true;
            }           
            $content = new WikitextContent( $this->params['content'] );
            $title = self::getStyleTitle();
            $wp = new WikiPage($title);
            $status = $wp->doEditContent($content, 'bot edit');
            $result->addValue($this->getModuleName(), 'res', ResponseGenerator::getArr(ResponseGenerator::SUCCESS));
            CommonStyle::clearCache();
            return true;
        }
        if ($this->params['task'] == 'get'){
            $result->addValue(null, $this->getModuleName(), CommonStyle::getStyle());
            $title = self::getStyleTitle();
            $count = $title->estimateRevisionCount();
            $result->addValue(null, $this->getModuleName, $count );
            $result->addValue(null, $this->getModuleName, $title->getFullUrl(['action'=>'history']) );
            return true;
        }
        if ($this->params['task'] == 'reset'){
            $title = self::getStyleTitle();
            $wp = new WikiPage($title);
            $status = $wp->doEditContent( new WikitextContent('-'), 'bot edit');
            $result->addValue($this->getModuleName(), 'res', ResponseGenerator::getArr(ResponseGenerator::SUCCESS));
            CommonStyle::clearCache();
            return true;

        } 
        if ($this->params['task'] == 'addtocollection'){
            if ( !isset($this->params['name']) || !isset($this->params['content']) ){
                $result->addValue($this->getModuleName(), 'res', ResponseGenerator::getArr(ResponseGenerator::ERROR_MISSING_ARG));
                return true;
            }
            CommonStyle::insertSiteCss($this->params['name'], $this->params['content']);
            $result->addValue($this->getModuleName(), 'res', ResponseGenerator::getArr(ResponseGenerator::SUCCESS));
            return true;
        } 
        if ($this->params['task'] == 'opencollection'){
            $dbr = wfGetDB(DB_SLAVE);
            $r = $dbr->select(
                    'common_css',
                    array(
                        'css_id',
                        'css_name',
                        'css_content',
                        'update_date',
                    ),
                    array(
                        'css_status = 2',
                    ),
                    __METHOD__
                );
            $result->addValue($this->getModuleName(), "collection", (array)$r);
            return true;
        }
        if ($this->params['task'] == 'usecollection'){
            if ( isset($this->params['id']) ){
                $result->addValue($this->getModuleName(), 'res', ResponseGenerator::getArr(ResponseGenerator::ERROR_MISSING_ARG));
                return true;
            } 
            $r = $dbr->select(
                    'common_css',
                    array(
                        'css_id',
                        'css_name',
                        'css_content',
                        'update_date',
                    ),
                    array(
                        'css_id' => $this->params['id'],
                    ),
                    __METHOD__
                );
            if ($r != null){
                foreach ($r as $key => $value) {
                    $res = $value['css_content'];
                    break;
                }
            } else {
                $result->addValue($this->getModuleName(), 'res', ResponseGenerator::getArr(ResponseGenerator::ERROR_UNKNOWN));
                return true;
            }
            $content = new WikitextContent( $res );
            $title = self::getStyleTitle();
            $wp = new WikiPage($title);
            $status = $wp->doEditContent( $content, 'bot edit');
            $result->addValue($this->getModuleName(),'res', $status);            
            $result->addValue($this->getModuleName(), 'res', ResponseGenerator::getArr(ResponseGenerator::SUCCESS));
            CommonStyle::clearCache();
            return true;
        }   
        if ($this->params['task'] == 'deletefromcollection'){
            if ( isset($this->params['id']) ){
                $result->addValue($this->getModuleName(), 'res', ResponseGenerator::getArr(ResponseGenerator::ERROR_MISSING_ARG));
                return true;
            } 
            $dbr->update(
                    'common_css',
                    array(
                        'css_status' => 0,
                    ),
                    array(
                        'css_id' => $this->params['id'],
                    ),
                    __METHOD__
                );  
            if ($dbr->affectedRows() > 0){
                $result->addValue($this->getModuleName(), 'res', ResponseGenerator::getArr(ResponseGenerator::SUCCESS));
            } else {
                $result->addValue($this->getModuleName(), 'res', ResponseGenerator::getArr(ResponseGenerator::ERROR_UNKNOWN));
            } 
            return true;               
        }
    } 
    public function getAllowedParams() {
        return array(
            'task' => array(
                ApiBase::PARAM_REQUIRED => true,
                ApiBase::PARAM_TYPE => 'string'
            ),
            'content' => array(
                ApiBase::PARAM_REQUIRED => false,
                ApiBase::PARAM_TYPE => 'string'
            ),
            'name' => array(
                ApiBase::PARAM_REQUIRED => false,
                ApiBase::PARAM_TYPE => 'string'
            ),
            'id' => array(
                ApiBase::PARAM_REQUIRED => false,
                ApiBase::PARAM_TYPE => 'integer'
            ),
        );
    }
    public function needsToken(){
        return 'csrf';
    }
}
?>