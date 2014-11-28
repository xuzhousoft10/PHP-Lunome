<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Media\Detail as MediaDetail;
use X\Module\Lunome\Service\Movie\Service;

/**
 * The action class for movie/ignore action.
 * @method \X\Module\Lunome\Service\Movie\Service getMovieService()
 * @author Unknown
 */
class Detail extends MediaDetail {
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Detail::afterFindTheModel()
     */
    protected function afterFindTheModel($media) {
        $media['region'] = $this->getMovieService()->getRegionById($media['region_id'])->name;
        $media['language'] = $this->getMovieService()->getLanguageById($media['language_id'])->name;
        $media['category'] = $this->getMovieService()->getCategoriesByMovieId($media['id']);
        foreach ( $media['category'] as $index => $category ) {
            $media['category'][$index] = $category->name;
        }
        $media['category'] = implode('，', $media['category']);
        return $media;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Detail::getShareMessage()
     */
    protected function getShareMessage($mediaId, $myMark) {
        $message = '';
        $categories = $this->getMovieService()->getCategoriesByMovieId($mediaId);
        if ( 0 < count($categories) ) {
            $index = rand(0, count($categories)-1);
            /* @var $category \X\Module\Lunome\Model\Movie\MovieCategoryModel */
            $category = $categories[$index];
            switch ( $myMark ) {
            case Service::MARK_INTERESTED :
                $message = $category->beg_message;
                break;
            case Service::MARK_WATCHED :
                $message = $category->recommend_message;
                break;
            default:
                $message = $category->share_message;
                break;
            }
        }
        
        if ( empty($message) ) {
            switch ( $myMark ) {
            case Service::MARK_INTERESTED :
                $message = "怀着各种复杂与激动的心情， 我来到了这里， 我抬头， 望了望天，想起了你，此时此刻， 我的心情不是别人所能理解的，土豪，请我看场《{name}》呗？";
                break;
            case Service::MARK_WATCHED :
                $message = "看完《{name}》， 我和我的小伙伴们都惊呆了！ GO！ GO! GO! ";
                break;
            default:
                $message = "";
                break;
            }
        }
        return $message;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Detail::getMarkStyles()
     */
    protected function getMarkStyles() {
        return array(
            Service::MARK_UNMARKED      => 'warning',
            Service::MARK_INTERESTED    => 'success',
            Service::MARK_WATCHED       => 'info',
            Service::MARK_IGNORED       => 'default'
        );
    }
}