<?php

namespace Gurinder\LaravelAcl\Contracts;


interface AclLedgerContract
{

    /**
     * @param bool $paginate
     * @param int  $page
     * @param int  $perPage
     * @return mixed
     */
    public function getRoles($paginate = false, $page = 1, $perPage = 15);

    /**
     * @param bool $paginate
     * @param int  $page
     * @param int  $perPage
     * @return mixed
     */
    public function getPermissions($paginate = false, $page = 1, $perPage = 15);

    /**
     * @param       $items
     * @param null  $page
     * @param int   $perPage
     * @param array $options
     * @return mixed
     */
    public function paginate($items, $page = null, $perPage = 15, $options = []);

    /**
     * @return mixed
     */
    public function reset();

    /**
     * @param $user
     * @return mixed
     */
    public function getUserAcl($user);
}