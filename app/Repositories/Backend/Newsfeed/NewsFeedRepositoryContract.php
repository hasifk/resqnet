<?php
namespace App\Repositories\Backend\Newsfeed;

/**
 * Interface UserRepositoryContract
 * @package App\Repositories\Frontend\User
 */
interface NewsFeedRepositoryContract {

    /**
     * @param $id
     * @return mixed
     */
    public function find($id);

    public function getNewsfeedPaginated();

    public function getMyNewsFeeds($id);

    public function newsFeedSearch($request);

    public function save($request);

    public function delete($id);


}
