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