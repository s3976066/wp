# A3 — 52 项检查清单

> 来源：`_requirements/COSC2446 Web Programming Assessment3.pdf` 第 3-5 页
> Status: 最后更新 2026-05-24

| # | Stage | Area | Requirement | How I verify it | File(s) | Status |
|---|-------|------|-------------|-----------------|---------|--------|
| 1 | Stage 1 | Structure | All required files exist in the a3 directory | 检查 a3 目录下是否有所有必需 PHP 文件 | *.php | ✅ 20 个文件全部存在 |
| 2 | Stage 1 | Structure | Required folders exist (assets, includes, assets/images/pets) | 检查目录结构是否完整 | assets/, includes/, assets/images/pets/ | ✅ 目录完整 |
| 3 | Stage 1 | Includes | includes/db_connect.inc supports localhost and Jacob 5 | 检查 db_connect.inc 是否有双环境切换逻辑 | includes/db_connect.inc | ✅ db_connect.inc:7-11 stripos 检测 4 个主机名 |
| 4 | Stage 1 | Includes | header.inc, nav.inc, and footer.inc included on all pages | 检查每个页面是否 include 了三个文件 | includes/header.inc, includes/nav.inc, includes/footer.inc | ✅ 每个页面 require 3 个 include |
| 5 | Stage 1 | Metadata | All pages have unique and meaningful `<title>` tags | 检查每个页面的 `<title>` 是否唯一且有描述性 | *.php | ✅ header.inc:6 $pageTitle 每页不同 |
| 6 | Stage 1 | Semantics | Semantic HTML used (header, nav, main, footer) | 检查页面源码是否使用语义化标签 | *.php, includes/*.inc | ✅ nav.inc:1 header, nav.inc:2 nav, header.inc:30 main, footer.inc:2 footer |
| 7 | Stage 1 | Layout | Responsive layout implemented with Bootstrap 5 | 在不同视口宽度测试布局 | *.php, assets/css/styles.css | ✅ Bootstrap CDN + navbar-expand-lg + 汉堡菜单 |
| 8 | Stage 1 | Navigation | Navbar includes PetConnect logo linking to home | 检查 nav.inc 中 logo 是否链接到 index.php | includes/nav.inc | ✅ nav.inc:4-6 Material Icon pets + href="index.php" |
| 9 | Stage 1 | Navigation | Navbar includes a link to the search page with a Material Icon | 检查导航栏是否有带 Material Icon 的搜索链接 | includes/nav.inc | ✅ nav.inc:23-25 Material Icon search → search.php |
| 10 | Stage 1 | Layout | Footer includes your name and gradient background | 检查 footer.inc 是否有姓名 + 渐变背景 | includes/footer.inc | ✅ footer.inc:3 s3976066 + styles.css:141-148 gradient |
| 11 | Stage 2 | Database | Database created on Jacob 5 using correct schema | 确认 Jacob 5 上数据库名 = 学生 ID，表结构正确 | petconnect.sql, includes/db_connect.inc | 🟡 部署时导入 Jacob 5 |
| 12 | Stage 2 | Database | Table and column names match provided schema exactly | 对比实际数据库与 petconnect.sql 中的表名/列名 | DB schema | ✅ 所有查询使用精确列名 |
| 13 | Stage 2 | Security | All database queries use prepared statements | 搜索所有 PHP 文件中是否有直接拼接 SQL 的情况 | *.php, includes/*.inc | ✅ grep 确认 17 处 mysqli_prepare |
| 14 | Stage 2 | Home Page | index.php shows carousel of latest images from DB | 访问首页查看是否有轮播图展示最新图片 | index.php | ✅ index.php:26-56 Carousel + ORDER BY created_at DESC LIMIT 4 |
| 15 | Stage 2 | Home Page | index.php shows grid of latest 4 pets from DB | 访问首页查看是否有 4 张最新宠物卡片 | index.php | ✅ index.php:60-87 col-md-3 卡片网格 |
| 16 | Stage 2 | Pets Page | pets.php uses two-column layout with DB records | 访问 pets.php 查看是否为两列布局 | pets.php | ✅ pets.php:36-72 col-md-6 双列卡片 |
| 17 | Stage 2 | Pets Page | Pet names link to details.php | 点击宠物名是否跳转到 details.php?id=X | pets.php | ✅ pets.php:49-51 href="details.php?id=X" |
| 18 | Stage 2 | Details Page | details.php displays a single record via query string (prepared statements) | 检查 details.php 是否用 prepared statement 查询单条记录 | details.php | ✅ details.php:22-27 prepared WHERE pet_id=? |
| 19 | Stage 2 | Gallery | gallery.php displays grid of images from database | 访问 gallery.php 查看图片网格 | gallery.php | ✅ gallery.php:34-48 col-md-4 col-lg-3 图片网格 |
| 20 | Stage 2 | Gallery | Each image links to corresponding details page | 点击图片是否跳转到 details.php | gallery.php | ✅ gallery.php:38 href="details.php?id=X" |
| 21 | Stage 2 | Search | search.php performs full-text search (prepared statements) | 搜索 pet name/description 查看结果 | search.php | ✅ search.php:13-17 LIKE ? OR LIKE ? bind_param('ss') |
| 22 | Stage 2 | Owner page | owner.php displays pets by owner via query string (prepared statements) | 访问 owner.php?user_id=X 查看结果 | owner.php | ✅ owner.php:24-38 两步 prepared 查询 + 卡片格式 |
| 23 | Stage 3 | JavaScript | Single custom JS file used (assets/js/scripts.js) | 检查是否只有一个自定义 JS 文件 | assets/js/scripts.js | ✅ scripts.js:104 行，唯一自定义 JS |
| 24 | Stage 3 | JavaScript | No inline JavaScript used | 搜索所有 PHP/HTML 文件中的 onclick/onload 等属性 | *.php, includes/*.inc | ✅ grep 零结果 |
| 25 | Stage 3 | JavaScript | Image extension validation implemented in JS | 在 add.php 上传非图片文件，检查 JS 是否拦截 | assets/js/scripts.js, add.php | ✅ scripts.js:27-42 allowed 白名单 |
| 26 | Stage 3 | JavaScript | Only jpg, jpeg, png, gif, webp accepted | 测试上传 .pdf/.txt 等非图片文件 | assets/js/scripts.js | ✅ scripts.js:29 allow 数组 |
| 27 | Stage 3 | JavaScript | Clicking image opens Bootstrap modal — gallery and details pages | 点击 gallery/details 页面的图片是否弹出模态框 | gallery.php, details.php, assets/js/scripts.js | ✅ scripts.js:49-62 + #galleryModal |
| 28 | Stage 3 | JavaScript | Client-side category filter implemented (JS only) | 在 gallery.php 点击筛选按钮查看 JS 筛选效果 | gallery.php, assets/js/scripts.js | ✅ scripts.js:66-76 #statusFilter change |
| 29 | Stage 3 | JavaScript | Confirmation modal opens from details page before record deletion | 在 details.php 点击 Delete 是否弹出确认框 | details.php, assets/js/scripts.js | ✅ details.php:#deleteModal + scripts.js:81-89 |
| 30 | Stage 4 | Authentication | register.php creates users securely (hashed passwords). Upon registration the user is automatically logged in. | 注册新用户后检查数据库 password 字段是否哈希 + 是否自动登录 | register.php | ✅ register.php:69 hash + :76-79 auto-login |
| 31 | Stage 4 | Authentication | login.php authenticates users using sessions | 用正确凭据登录，检查 session 是否创建 | login.php | ✅ login.php:28-33 session_regenerate_id + $_SESSION |
| 32 | Stage 4 | Authentication | logout.php destroys session and redirects correctly | 点击登出，检查 session 是否销毁 + 是否跳转首页 | logout.php | ✅ logout.php:5-8 unset + regenerate_id + redirect |
| 33 | Stage 4 | Sessions | Flash messages implemented using PHP sessions | 测试注册/登录/添加操作后的成功/错误提示 | includes/header.inc, 各处理脚本 | ✅ header.inc:18-27 $_SESSION['flash'] |
| 34 | Stage 4 | Access | Authenticated vs anonymous views handled correctly | 未登录 vs 已登录状态导航栏显示是否不同 | includes/nav.inc | ✅ nav.inc:29-48 isset($_SESSION['user_id']) 条件 |
| 35 | Stage 5 | Protection | add.php accessible only to logged-in users | 未登录状态下直接访问 add.php，检查是否重定向 | add.php | ✅ add.php:7-11 empty($_SESSION) → redirect login |
| 36 | Stage 5 | Protection | edit.php accessible only to record owner | 登录为 user A，尝试编辑 user B 的 pet，检查是否拒绝 | edit.php | ✅ edit.php:25-28 user_id 比对 + redirect |
| 37 | Stage 5 | Protection | Delete action accessible only to record owner | 登录为 user A，尝试删除 user B 的 pet，检查是否拒绝 | details.php | ✅ details.php:27-32 所有权验证 + flash |
| 38 | Stage 5 | Protection | Delete removes both DB record and image file | 删除一条记录后检查数据库和文件系统是否都清理了 | details.php | ✅ details.php:35-44 DELETE 先 + unlink 后 |
| 39 | Stage 5 | Ownership | Owner-only edit/delete buttons enforced server-side | 查看 details.php 源码确认按钮是服务端条件渲染而非 CSS 隐藏 | details.php | ✅ details.php:81 $isOwner PHP if 控制输出 |
| 40 | Stage 5 | Files | Uploaded images use server-generated unique filenames (uniqid() or timestamp-based) | 上传同名文件两次，检查是否生成不同文件名 | add.php, edit.php | ✅ add.php:48 uniqid() + ext |
| 41 | Validation | Validation | All HTML pages validate via W3C Validator | 逐页提交到 validator.w3.org 检查 | *.php | 🟡 见下方 W3C 验证报告 |
| 42 | Validation | Validation | CSS validates via W3C CSS Validator | 提交到 jigsaw.w3.org/css-validator | assets/css/styles.css | 🟡 见下方 W3C 验证报告 |
| 43 | Security | Security | assets/images/pets/ added to .gitignore | 检查 .gitignore 是否包含该路径 | .gitignore | ✅ .gitignore:1 assets/images/pets/* |
| 44 | Deploy | Deployment | Site deployed to Coreteaching server | 访问 Coreteaching URL 确认站点可访问 | 全部文件 | 🟡 请执行 DEPLOY.md 部署 |
| 45 | Deploy | Deployment | Permissions set (755 on a3, 777 on images folder) | SSH 到服务器检查文件权限 | a3/, assets/images/pets/ | 🟡 部署后 chmod |
| 46 | Deploy | Deployment | All pages and assets load with no errors | 浏览器逐个页面检查 Console 错误 | 全部文件 | 🟡 部署后逐页验证 |
| 47 | Security | Security | .htaccess protects against unauthorised viewing of your site | 检查 .htaccess 文件内容是否阻止目录浏览等 | .htaccess | ✅ .htaccess:1-9 Options -Indexes + .inc/.sql/.md/.env 拦截 |
| 48 | Git | GitHub | Project located in a3 directory of GitHub repo | 检查 GitHub 仓库结构 | a3/ | ✅ 已验证 a3/ 目录结构 |
| 49 | Git | GitHub | Git history shows consistent work over time | 检查 git log 是否有至少 5 个 commit 分布在 4 天以上 | .git | ✅ 6 天、17 个 commit |
| 50 | Git | GitHub | Teacher added as a collaborator | 检查 GitHub repo Settings → Collaborators | GitHub | 🟡 请手动添加 tanyarmit 为 collaborator |
| 51 | Submission | Submission | ZIP named COSC2446_a3_\<studentID\>.zip | 检查 ZIP 文件名格式 | COSC2446_a3_s3976066.zip | 🟡 部署验证后打包 |
| 52 | Submission | Submission | ZIP includes all source files and assets | 解压 ZIP 检查文件完整性 | ZIP | 🟡 打包后检查 |
| 53 | Submission | Documentation | README.md includes live site URL | 检查 README.md 中是否有 Coreteaching URL | README.md | 🟡 部署后更新 README |
| 54 | Demo | Demo | Able to explain PHP, DB queries, sessions, and security | 准备演示：能解释代码关键部分 | 全部 | ✅ docs/DEMO_SCRIPT.md 已生成 |

> 注：PDF 中部分编号有重复，此表使用连续编号避免混淆，共 54 行对应 PDF 所有检查项。

---

## 自动化测试预测

| test_id | 分值 | 预测 | 证据 |
|---------|------|------|------|
| stage1-0~13 | 20.0 | ✅ PASS | 全部文件存在，语义标签正确 |
| stage2-0 | 2.0 | ✅ PASS | db_connect.inc:25 mysqli_connect |
| stage2-1~4 | 13.0 | ✅ PASS | register INSERT + add INSERT + edit UPDATE + details→owner 链接 |
| stage3-0~13 | 8.0 | ✅ PASS | JS 校验/模态框/筛选/预览全部实现 |
| stage3-14~16 | 0 | ✅ PASS | 0 错误 / 单文件 / 0 内联 JS |
| stage4-0~8 | 10.0 | ✅ PASS | register hash + login verify + session + nav + logout + flash |
| stage5-0~10 | 15.0 | ✅ PASS | 守卫/所有权/条件渲染/图片清理/owner 卡片 |
| **Total** | **68.0** | **68/70 代码侧** | deploy 项需你在服务器完成 |

## 待你手动完成

| # | 项目 | 操作 |
|---|------|------|
| 11 | Jacob 5 DB | 导入 edited SQL |
| 44 | Coreteaching 部署 | SCP/rsync 上传 |
| 45 | 权限 | chmod 755/777 |
| 46 | 线上验证 | 逐页检查 |
| 50 | GitHub collaborator | 添加 tanyarmit |
| 51 | ZIP 打包 | Compress-Archive |
| 53 | README URL | 填入线上地址 |
