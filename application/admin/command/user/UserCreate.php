<?php
/**
 * Created by PhpStorm.
 * User: Happy233
 * Date: 2019/2/21
 * Time: 19:57
 */

namespace app\admin\command\user;

use app\index\model\User;
use app\index\validate\User as UserValidator;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\console\Table;
use think\Exception;
use think\exception\ValidateException;
use think\helper\Hash;

class UserCreate extends Command
{
    protected function configure()
    {
        $this->setName('user:create')
            ->addArgument('nick', Argument::REQUIRED, "user name")
            ->addArgument('email', Argument::REQUIRED, "user email")
            ->addArgument('password', Argument::REQUIRED, "user password")
            ->addArgument('permission', Argument::OPTIONAL, "user permission", 0)
            ->setDescription('Create a user');
    }

    protected function execute(Input $input, Output $output)
    {
        $nick = trim($input->getArgument('nick'));
        $email = trim($input->getArgument('email'));
        $password = trim($input->getArgument('password'));
        $permission = trim($input->getArgument('permission'));

        $data = [
            'nick' => $nick,
            'email' => $email,
            'password' => Hash::make($password),
            'permission' => $permission,
        ];
        $v = new UserValidator();
        if (!$v->batch(true)->check($data)) {
            $table = new Table();
            $table->setHeader(['Error']);
            $error = [];
            foreach ($v->getError() as $e) {
                $error[] = [$e];
            }
            $table->setRows($error);
            $this->table($table);
            return;
        }
        if (User::userExists($data)) {
            throw new Exception("用户已经存在");
        }
        $user = new User();
        $user->save($data);
        $output->info("添加成功");
        return;
    }
}