# 经验总结 — 错误与正确做法

## 数据库相关

| # | 错误 | 现象 | 正确做法 |
|---|------|------|----------|
| 1 | DB 里 `image_path` 存的是原始文件名（如 `Buddy.jpg`），但实际文件已重命名为数字（`1.jpg`） | 页面图片全部 404 | 先确认文件命名规则，再用 `UPDATE pets SET image_path = 'X.jpg' WHERE pet_id = X` 修正 |
| 2 | 创建了 `pets` 表但忘了创建 `users` 表 | 注册/登录功能报错，外键约束失败 | 两个表一起建，先建 `users` 再建 `pets`（pets 依赖 users 的 FK） |
| 3 | `pets` 表缺少 `user_id` 外键列 | 无法关联宠物和主人 | `ALTER TABLE pets ADD COLUMN user_id INT NOT NULL DEFAULT 1;` 然后 `ADD FOREIGN KEY` |
| 4 | Seed 密码哈希是 `password` 的 bcrypt hash，但实际密码是 `password123` | 登录全部失败 | 用 `password_hash('password123', PASSWORD_BCRYPT)` 重新生成哈希，批量 UPDATE |
| 5 | Jacob 5 数据库在校外无法直连（`talsprddb02.int.its.rmit.edu.au` 仅校内解析） | 连接超时 / 无法解析主机名 | 必须在校内网络或通过 MyDesktop 远程桌面连接，然后用 SQLyog 直连 Jacob 5 |
| 6 | 用 SQLyog 粘贴 SQL 时只粘贴了部分内容（如单个字母 "e"） | 执行失败，表结构不完整 | 复制完整 SQL 内容后再粘贴，执行完用 `SELECT COUNT(*) FROM users/pets` 验证 |

## 部署相关

| # | 错误 | 现象 | 正确做法 |
|---|------|------|----------|
| 7 | SSH 隧道 `ssh -L 3307:... s3976066@titan.csit.rmit.edu.au` 使用错误密码 | 反复提示密码错误 | 确认 Jacob 5 密码（通过 access.csit.rmit.edu.au 设置）与 Coreteaching SSH 密码是分开的 |
| 8 | 本地 `.htaccess` 包含 Kerberos 认证指令（`AuthType Kerberos`、`Krb5Keytab` 等） | 本地 XAMPP 500 Internal Server Error | 用 `<IfModule auth_kerb_module>` 包裹 Kerberos 配置，本地自动跳过，服务器上正常工作 |
| 9 | 线上 URL 路径与本地不一致（`~/s3976066/wp/a3/` 而不是 `~/s3976066/a3/`） | README 里 URL 写错，别人点开 404 | 部署后先亲自访问确认 URL，再更新 README |

## PHP 语法 / 文件

| # | 错误 | 现象 | 正确做法 |
|---|------|------|----------|
| 10 | `details.php` 里 `<?php if ($isOwner): ?>` 后面缺少 `<?php endif; ?>` | PHP Parse Error 白屏 | 用 `php -l filename.php` 逐文件检查语法再提交 |
| 11 | CSS 文件取名 `style.css`（单数） | 自动评分器不识别，扣分 | 按照作业要求命名为 `styles.css`（复数），同时更新所有引用 `header.inc` |
| 12 | 重复的 `body { ... }` CSS 规则 | 样式覆盖混乱 | 每次加 CSS 前先搜索是否已有相同选择器 |

## 前端样式

| # | 错误 | 现象 | 正确做法 |
|---|------|------|----------|
| 13 | Bootstrap `.table` 默认白色背景（`--bs-table-bg: #fff`），而网站文字是白色 | 表格内容完全看不见（白底白字） | 在自定义 CSS 中覆盖：`--bs-table-bg: var(--bg-card);` 让表格匹配深色主题 |
| 14 | Owner 卡片未设置文字颜色 | 卡片背景深色但文字用 Bootstrap 默认灰色，对比度不足 | 卡片上加 `style="color: #f1f5f9;"` 确保白色文字 |
| 15 | `.card-title` 和 `.card-text` 被 Bootstrap 默认样式覆盖 | 卡片内文字颜色不一致 | 使用 `!important` 或更高优先级选择器覆盖 |

## Git 相关

| # | 错误 | 现象 | 正确做法 |
|---|------|------|----------|
| 16 | `docs/` 文件夹和 `CLAUDE.md` 被 git 误删 | 提交后发现文件消失 | 用 `git checkout <commit> -- <path>` 从历史恢复，提交前用 `git status` 仔细检查改动范围 |
| 17 | `git add .` 或大范围 add 时带上了不该提交的文件 | 修改范围失控，意外删除文件进入 .gitignore | 用 `git add <具体文件名>` 逐个添加，不用通配符 |

## 关键验证清单（每次部署/提交前）

1. `php -l *.php includes/*.inc` — 所有 PHP 文件无语法错误
2. `grep -r "onclick\|onload\|onchange" *.php includes/ assets/js/` — 零内联 JavaScript
3. `grep -r "[\\x{4e00}-\\x{9fff}]" *.php includes/ assets/` — 零中文字符
4. `git status` — 确认所有改动都是预期的
5. 本地 `http://localhost/wp-repo/a3/index.php` 全部页面正常
6. Jacob 5：`SELECT COUNT(*) FROM users;` 返回 5，`SELECT COUNT(*) FROM pets;` 返回 8
