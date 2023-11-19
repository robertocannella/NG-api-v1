<?php

namespace Cannella\Sessions;

trait PersistentProperties
{
    private string $cookie = 'rc_auth';
    private string $table_sess = 'sessions';
    private string $table_users = 'users';
    private string $table_autologin = 'autologin';
    private string $col_sid = 'sid';
    private string $col_expiry = 'expiry';
    private string $col_name = 'username';
    private string $col_data = 'data';
    private string $col_ukey = 'user_key';
    private string $col_token = 'token';
    private string $col_created = 'created';
    private string $col_used = 'used';
    private string $sess_persist = 'remember';
    private string $sess_uname = 'uname';
    private string $sess_auth = 'authenticated';
    private string $sess_validate = 'validated';
    private string $sess_ukey = 'user_key';

}