# webman-migration
 和laravel migration 使用方法类似
- php webman migrate:create create_users_table 生成迁移文件
- php webman migrate:create create_users_table --path=admin 生成迁移文件是指定目录
- php webman migrate:run  执行迁移
- php webman migrate:run  --path=admin 执行指定目录的迁移
- php webman migrate:rollback 回滚迁移
- php webman migrate:status 查看迁移状态
- php webman migrate:fresh 
- php webman seed:run 执行数据填充
- php webman seed:create UserSeeder 生成数据填充文件


指定数据库连接
```
$this->schema()->setConnection(Db::connection('mysql2'))->create('orders', function (Blueprint $table) {
$table->id();
$table->timestamps();
});
```
dev 
 `composer require pxianyu/migrations:dev-dev`
 在同一个host，同一个端口下，指定不同的库
 
创建数据库
`php webman create:database test`
执行迁移
`php webman migrate:run --database=test`
填充数据
`- php webman seed:run --database=test`
