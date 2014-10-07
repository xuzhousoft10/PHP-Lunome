<?php
/**
 * This file implements the service Movie
 */
namespace X\Module\Lunome\Service\Movie;

/**
 * Use statement
 */
use X\Module\Lunome\Model\MovieModel;
use X\Module\Lunome\Model\MovieUserWatchedModel;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Service\XDatabase\Core\SQL\Expression as SQLExpression;
use X\Module\Lunome\Model\MovieUserIgnoredModel;
use X\Module\Lunome\Model\MovieUserInterestedModel;

/**
 * The service class
 */
class Service extends \X\Core\Service\XService {
    public function getUnmarkedMovies($condition=array(), $length=0, $position=0) {
        /* Generate basic condition to ignore marked movies. */
        $markedMovieCondition = array('movie_id'=>new SQLExpression('movies.id'), 'account_id'=>0);
        $markedMovieActiveColumn = array('movie_id');
        $watchedMovies = MovieUserWatchedModel::query()->activeColumns($markedMovieActiveColumn)->find($markedMovieCondition);
        $ignoredMovies = MovieUserIgnoredModel::query()->activeColumns($markedMovieActiveColumn)->find($markedMovieCondition);
        $interestedMovies = MovieUserInterestedModel::query()->activeColumns($markedMovieActiveColumn)->find($markedMovieCondition);
        $basicCondition = ConditionBuilder::build()
            ->notExists($watchedMovies)
            ->andAlso()
            ->notExists($ignoredMovies)
            ->andAlso()
            ->notExists($interestedMovies);
        
        $movies = MovieModel::model()->findAll($basicCondition, $length, $position);
    }
}