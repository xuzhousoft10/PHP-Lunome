<?php
namespace X\Module\Movie\Service\Movie\Core\Instance;
/**
 * 
 */
use X\Util\Service\Instance\Model;
use X\Module\Movie\Service\Movie\Core\Model\MovieInvitationModel;
/**
 * 
 */
class Invitation extends Model {
    /**
     * @return string
     */
    protected static function getModelClass() {
        return get_class(MovieInvitationModel::model());
    }
}