## 关于Laravel Tool

使用前需要将 `ToolServiceProvider.php`在`app.php`中的`providers`引入

- 1.帮助函数, 位于 `src/Common/helper.php`

- 2.模型自动加载<br>
使用方法
```php
model('User', ["name" => '张三']);
model('User');
```
- 3.Service自动加载<br>
```php
service('User');
```
- 4.Dcat Admin 仓库自动加载<br>
```php
repository('User', ['orders']);
repository('User');
```

- 5.artisan命令 生成多语言表
- 6.artisan命令 生成数据库表结构文档
  <br><br><br>
  remote：对密码身份验证的支持已于 2021 年 8 月 13 日移除。请改用个人访问令牌。远程：请参阅 https:github.blog2020-12-15-token-authentication-requirements-for-git-operations 了解更多信息。致命：'https：github.comhaoke12138laravel-tool.git 的身份验证失败

#### 自动加载使用方法和规则<br>
  使用方式如下
```php
    private function getModel(array $param = [])
    {
        return model('Navigation', $param); // 类似于 new /App/Models/Navigation($param) 不过使用的是他的一个单例
        // 第二个参数为目标在Models的相对命名空间并用.相连接
        // 如 命名空间为 \App\Models\Navigation, 则填写 Navigation
        // 如 命名空间为 \App\Models\Nav\Navigation, 则填写 Nav.Navigation
        
         // 调用service使用 命名空间为 \App\Services\Navigation, 则填写 Navigation
         // 调用repository使用 命名空间为 \App\Admin\Repositories\Navigation, 则填写 Navigation
    }

- 7.添加stub创建模板, 若有使用dcat会替换掉原有的dcat的model.stub和repository.stub