# A3 — 部署指南

> 目标服务器：Coreteaching (titan.csit.rmit.edu.au) + Jacob 5 数据库

---

## 1. 部署前检查

在本地确认以下事项后再上传：

- [ ] `.gitignore` 已包含 `assets/images/pets/*`（仅保留 `.gitkeep`）
- [ ] `.htaccess` 已配置 `Options -Indexes` + `.inc`/`.sql`/`.md`/`.env` 拦截
- [ ] `README.md` 已有标题，部署后填入线上 URL
- [ ] `includes/db_connect.inc` 中 Jacob 5 密码仍为占位符 `<I_WILL_FILL_THIS_IN_BEFORE_DEPLOY>` — **请替换为真实密码**
- [ ] 本地测试全部 11 个页面正常渲染
- [ ] 注册/登录/添加宠物/编辑/删除 流程正常
- [ ] 数据库 `petconnect` 有 5 个 users + 8 个 pets
- [ ] 图片文件 `assets/images/pets/1.jpg` ~ `8.jpg` 存在
- [ ] 确认 JS 无内联事件（`onclick`/`onload`）

**替换 db_connect.inc 密码**（第 16 行）：
```php
$dbPass = '<I_WILL_FILL_THIS_IN_BEFORE_DEPLOY>';
```
改为：
```php
$dbPass = '你的真实 Jacob 5 密码';
```

---

## 2. 上传文件到 Coreteaching

### 首次上传（SCP 全量复制）

```bash
# 从项目根目录执行
cd C:\xampp\htdocs\wp-repo
scp -r a3/ s3976066@titan.csit.rmit.edu.au:~/public_html/
```

### 后续更新（rsync 增量同步，推荐）

```bash
rsync -avz --exclude='assets/images/pets/*.jpg' --exclude='assets/images/pets/*.jpeg' --exclude='assets/images/pets/*.png' --exclude='assets/images/pets/*.gif' --exclude='assets/images/pets/*.webp' a3/ s3976066@titan.csit.rmit.edu.au:~/public_html/a3/
```

> rsync 会自动跳过未变更的文件，比 SCP 快得多。
> 排除图片文件是因为它们很大且不会改变。

### Coreteaching 可用服务器

| 服务器 | Hostname |
|--------|----------|
| Titan | titan.csit.rmit.edu.au |
| Jupiter | jupiter.csit.rmit.edu.au |
| Saturn | saturn.csit.rmit.edu.au |

---

## 3. 服务器端设置

### SSH 登录

```bash
ssh s3976066@titan.csit.rmit.edu.au
```

### 设置权限

```bash
cd ~/public_html
chmod 755 a3
chmod 777 a3/assets/images/pets
```

### 验证权限

```bash
ls -la ~/public_html/
ls -la ~/public_html/a3/assets/images/
```

期望输出：
```
drwxr-xr-x  ... a3/
drwxrwxrwx  ... assets/images/pets/
```

### 上传种子图片

8 张种子图片在 `.gitignore` 中，不会随 git 推送。需要单独上传：

```bash
# 从本地电脑执行
scp a3/assets/images/pets/{1,2,3,4,5,6,7,8}.jpg s3976066@titan.csit.rmit.edu.au:~/public_html/a3/assets/images/pets/
```

---

## 4. Jacob 5 数据库设置

### 数据库信息

| 项目 | 值 |
|------|-----|
| 主机 | talsprddb02.int.its.rmit.edu.au |
| 数据库名 | s3976066（学生 ID） |
| 用户名 | s3976066 |
| 密码 | 与 Coreteaching SSH 密码相同（需确认） |

### 通过 phpMyAdmin 导入

1. 访问 Jacob 5 phpMyAdmin（通常通过校内 VPN 或 SSH 隧道）
2. 选择数据库 `s3976066`
3. 导入 SQL 文件前**先编辑**，删除以下行：
   ```sql
   CREATE DATABASE IF NOT EXISTS petconnect ...;
   USE petconnect;
   ```
4. 只保留 `CREATE TABLE` 和 `INSERT` 语句
5. 确认导入后：
   ```sql
   SELECT COUNT(*) FROM users;   -- 应为 5
   SELECT COUNT(*) FROM pets;    -- 应为 8
   ```

### 备选：通过 SSH 隧道连接

```bash
# 建立 SSH 隧道将 Jacob 5 端口转发到本地
ssh -L 3307:talsprddb02.int.its.rmit.edu.au:3306 s3976066@titan.csit.rmit.edu.au
```
然后在本地用 MySQL 客户端连接 `localhost:3307`。

### db_connect.inc 自动检测

当前 `db_connect.inc` 已配置 `$server` 主机名检测（`stripos` 匹配 `rmit.edu.au` / `jupiter` / `saturn` / `titan`），上传后会自动切换到 Jacob 5 连接配置。

---

## 5. 线上验证清单

| # | 测试 | URL | 预期 |
|---|------|-----|------|
| 1 | 首页 | `~s3976066/a3/index.php` | 轮播图 + 4 张卡片正常显示 |
| 2 | 宠物总览 | `~s3976066/a3/pets.php` | 横幅 + 8 条宠物双列布局 |
| 3 | 画廊 | `~s3976066/a3/gallery.php` | 图片网格 + 下拉筛选可用 |
| 4 | 详情 | `~s3976066/a3/details.php?id=1` | Buddy 信息完整 + 主人卡片 |
| 5 | 主人页 | `~s3976066/a3/owner.php?user_id=1` | sarah 的 2 只宠物卡片 |
| 6 | 搜索 | `~s3976066/a3/search.php?q=Golden` | 找到 Buddy |
| 7 | 注册 | `~s3976066/a3/register.php` | 注册新用户 → 自动登录 → 跳转首页 |
| 8 | 登录 | `~s3976066/a3/login.php` | sarah_animal_lover / password123 → 登录成功 |
| 9 | 添加 | `~s3976066/a3/add.php` | 登录后访问 → 上传图片 → 添加成功 |
| 10 | 编辑 | `~s3976066/a3/edit.php?id=1` | 登录 sarah → 可编辑自己的宠物 |
| 11 | 登出 | `~s3976066/a3/logout.php` | 跳回首页，nav 显示 Login/Register |
| 12 | JS 无错误 | F12 Console | 所有页面 0 错误 |
| 13 | 图片 | 任意详情页 | 图片正常加载不 404 |
| 14 | 权限 | 上传测试图片 | assets/images/pets/ 可写 |

---

## 6. 更新 README.md

部署确认后，编辑 `a3/README.md`：

```markdown
# Assessment 3 — PetConnect
Live site: https://titan.csit.rmit.edu.au/~s3976066/a3/
```

---

## 7. 最终提交 + 标签

```bash
cd C:\xampp\htdocs\wp-repo

# 提交 README 更新（含线上 URL）
git add a3/README.md a3/docs/DEPLOY.md
git commit -m "deploy: live on titan, add README site URL and deploy guide"

# 可选的版本标签
git tag v1.0-submission

# 推送（含标签）
git push origin main
git push origin v1.0-submission
```

---

## 故障排查

| 问题 | 可能原因 | 解决 |
|------|---------|------|
| 500 错误 | 文件权限不对 | `chmod 755 *.php` |
| 图片 404 | 种子图片未上传 | 单独 SCP 上传 8 个 jpg |
| DB 连接失败 | 密码占位符未替换 | 修改 db_connect.inc 第 16 行 |
| 图片上传失败 | pets 目录权限 | `chmod 777 assets/images/pets` |
| 搜索无结果 | 表结构不匹配 | 确认 column 名与 schema 完全一致 |
| Session 不持久 | 服务器 PHP 配置 | 确认 session.save_path 可写 |
