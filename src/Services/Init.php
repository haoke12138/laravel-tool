<?php

namespace ZHK\Tool\Services;

use Illuminate\Support\Facades\Artisan;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\MountManager;

class Init extends Service
{
    /**
     * @deprecated 项目初始化
     * @param bool $sqlExecute sql是否执行
     * @throws Exceptions\NotFundException
     */
    public function init($sqlExecute = true)
    {
        $this->initDatabase();
        Artisan::call('admin:install');
        dump('完成dcat_admin表初始化!');

        $this->filePublish();
        dump('文件发布完成!');

        $this->replaceStubFile();
        dump('dact-admin存根文件替换完成!');

        $this->replaceTinymceFile();
        dump('dact-admin tinymce 编辑器文件 替换完成!');

        if ($sqlExecute) {
            $this->restoreBackup();
        }

    }

    /**
     * @deprecated 创建数据库用户和连接的数据库
     * @throws Exceptions\NotFundException
     */
    public function initDatabase()
    {
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        if (empty($database) || empty($username)) {
            throw $this->notFound('请检查.env 文件中 DB_DATABASE, DB_USERNAME 是否正确设置');
        }
        exec("mysql -uroot -e 'CREATE DATABASE `$database` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci'");
        exec("mysql -uroot -e \"create user '$username'@'localhost' identified by '$password';\"");
        exec("mysql -uroot -e 'grant all on $database.* to $username@localhost;'");
        dump('数据库和用户已经添加完成!');
    }

    /**
     * @deprecated 文件发布
     */
    public function filePublish()
    {
        foreach (\ZHK\Tool\ToolServiceProvider::$filepath as $key => $item) {
            if (is_file($key)) {
                $dir = explode('/', $item);
                $dir[count($dir) - 1] = '';
                $dir = join('/', $dir);
                if (!file_exists($dir)) {
                    mkdir($dir, 0755, true);
                }
                copy($key, $item);
            } else {
                // 复制文件夹内的内容
                $this->moveManagedFiles(new MountManager([
                    'from' => new Flysystem(new LocalAdapter($key)),
                    'to' => new Flysystem(new LocalAdapter($item)),
                ]));
            }
        }
    }

    /**
     * @deprecated 恢复备份sql
     */
    public function restoreBackup()
    {
        $database = env('DB_DATABASE');
        if (empty($database)) {
            throw $this->notFound('请检查.env文件 DB_DATABASE 配置是否正确');
        }

        $dir = storage_path('backup');
        if (file_exists($dir . '/.gitignore')) {
            $filename = $dir . '/' . last(scandir($dir));
            if (substr($filename, -4, 4) === '.sql') {
                dump('执行storage目录下的最新sql备份文件');
                exec("mysql -uroot $database < $filename");
            }
        } else {
            $filename = '';
            foreach (scandir(base_path()) as $item) {
                if (substr($item, -4, 4) === '.sql') {
                    $filename = $item;
                    break;
                }
            }

            dump('执行项目根目录下的sql备份文件');
            exec("mysql -uroot $database < $filename");
        }
    }

    /**
     * @deprecated 替换dcat存根文件
     */
    public function replaceStubFile()
    {
        $dir = base_path('vendor/dcat/laravel-admin/src/Scaffold/stubs');
        dump('开始替换dcat存根文件');

        // model
        unlink("$dir/model.stub");
        copy(__DIR__. '/../dcat-stub/model.stub', $dir.'/model.stub');
        exec('chmod 777 ' . $dir.'/model.stub');

        // repository
        unlink("$dir/repository.stub");
        copy(__DIR__. '/../dcat-stub/repository.stub', $dir.'/repository.stub');
        exec('chmod 777 ' . $dir.'/repository.stub');
    }

    /**
     * @deprecated 替换dcat-admin tinymce编辑器文件
     */
    public function replaceTinymceFile()
    {
        $filepath = base_path('vendor/dcat/laravel-admin/resources/views/form/display.blade.php');
        dump('开始替换dcat存根文件');
        unlink($filepath);
        copy(__DIR__. '/../../resources/views/admin/editor.blade.php', $filepath);
        exec("chmod 777 $filepath");
    }

    /**
     * Move all the files in the given MountManager.
     *
     * @param  \League\Flysystem\MountManager  $manager
     * @return void
     */
    protected function moveManagedFiles($manager)
    {
        foreach ($manager->listContents('from://', true) as $file) {
            if ($file['type'] === 'file' && (! $manager->has('to://'.$file['path']))) {
                $manager->put('to://'.$file['path'], $manager->read('from://'.$file['path']));
            }
        }
    }

}