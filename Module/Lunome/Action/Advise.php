<?php
/**
 * The action file for advise action.
 */
namespace X\Module\Lunome\Action;

/**
 * 
 */
use X\Module\Lunome\Util\Action\Visual;
use X\Library\Redmine\Client;

/**
 * The action class for advise action.
 * @author Unknown
 */
class Advise extends Visual { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $content, $email, $send ) {
        $this->getView()->loadLayout($this->getLayoutViewPath('Blank'));
        if ( 'send' !== $send ) {
            $name   = 'ADVISE_EDIT';
            $path   = $this->getParticleViewPath('Advise/Edit');
            $option = array();
            $data   = array();
        } else {
            if ( 0 !== strlen(trim($content)) ) {
                $redmine = new Client('http://114.215.148.203:8001', 'michaelluthor', 'ginhappy@1215');
                $subject = sprintf('%s : 来自用户的建议或意见', date('Y-m-d H:i:s', time()));
                $projectId = $redmine->project()->getIdByName('Lunome');
                $trackerId = $redmine->tracker()->getIdByName('意见建议');
                $userId = $redmine->user()->getIdByUsername('michaelluthor');
                if ( 0 !== strlen(trim($content)) ) {
                    $content .= "\n";
                    $content .= "\n";
                    $content .= sprintf('反馈者邮箱：%s', $email);
                }
                $redmine->issue()->create(array(
                    'subject'          => $subject,
                    'description'      => $content,
                    'project_id'       => $projectId,
                    'tracker_id'       => $trackerId,
                    'assigned_to_id'   => $userId,
                ));
            }
            
            $name   = 'ADVISE_SENDED';
            $path   = $this->getParticleViewPath('Advise/Sended');
            $option = array();
            $data   = array();
        }
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}