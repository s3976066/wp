# A3 — 52 项检查清单

> 来源：`_requirements/COSC2446 Web Programming Assessment3.pdf` 第 3-5 页
> Status: Not started = 未开始, Done = 已完成

| # | Stage | Area | Requirement | How I verify it | File(s) | Status |
|---|-------|------|-------------|-----------------|---------|--------|
| 1 | Stage 1 | Structure | All required files exist in the a3 directory | 检查 a3 目录下是否有所有必需 PHP 文件 | *.php | Not started |
| 2 | Stage 1 | Structure | Required folders exist (assets, includes, assets/images/pets) | 检查目录结构是否完整 | assets/, includes/, assets/images/pets/ | Not started |
| 3 | Stage 1 | Includes | includes/db_connect.inc supports localhost and Jacob 5 | 检查 db_connect.inc 是否有双环境切换逻辑 | includes/db_connect.inc | Not started |
| 4 | Stage 1 | Includes | header.inc, nav.inc, and footer.inc included on all pages | 检查每个页面是否 include 了三个文件 | includes/header.inc, includes/nav.inc, includes/footer.inc | Not started |
| 5 | Stage 1 | Metadata | All pages have unique and meaningful `<title>` tags | 检查每个页面的 `<title>` 是否唯一且有描述性 | *.php | Not started |
| 6 | Stage 1 | Semantics | Semantic HTML used (header, nav, main, footer) | 检查页面源码是否使用语义化标签 | *.php, includes/*.inc | Not started |
| 7 | Stage 1 | Layout | Responsive layout implemented with Bootstrap 5 | 在不同视口宽度测试布局 | *.php, assets/css/styles.css | Not started |
| 8 | Stage 1 | Navigation | Navbar includes PetConnect logo linking to home | 检查 nav.inc 中 logo 是否链接到 index.php | includes/nav.inc | Not started |
| 9 | Stage 1 | Navigation | Navbar includes a link to the search page with a Material Icon | 检查导航栏是否有带 Material Icon 的搜索链接 | includes/nav.inc | Not started |
| 10 | Stage 1 | Layout | Footer includes your name and gradient background | 检查 footer.inc 是否有姓名 + 渐变背景 | includes/footer.inc | Not started |
| 11 | Stage 2 | Database | Database created on Jacob 5 using correct schema | 确认 Jacob 5 上数据库名 = 学生 ID，表结构正确 | petconnect.sql, includes/db_connect.inc | Not started |
| 12 | Stage 2 | Database | Table and column names match provided schema exactly | 对比实际数据库与 petconnect.sql 中的表名/列名 | DB schema | Not started |
| 13 | Stage 2 | Security | All database queries use prepared statements | 搜索所有 PHP 文件中是否有直接拼接 SQL 的情况 | *.php, includes/*.inc | Not started |
| 14 | Stage 2 | Home Page | index.php shows carousel of latest images from DB | 访问首页查看是否有轮播图展示最新图片 | index.php | Not started |
| 15 | Stage 2 | Home Page | index.php shows grid of latest 4 pets from DB | 访问首页查看是否有 4 张最新宠物卡片 | index.php | Not started |
| 16 | Stage 2 | Pets Page | pets.php uses two-column layout with DB records | 访问 pets.php 查看是否为两列布局 | pets.php | Not started |
| 17 | Stage 2 | Pets Page | Pet names link to details.php | 点击宠物名是否跳转到 details.php?id=X | pets.php | Not started |
| 18 | Stage 2 | Details Page | details.php displays a single record via query string (prepared statements) | 检查 details.php 是否用 prepared statement 查询单条记录 | details.php | Not started |
| 19 | Stage 2 | Gallery | gallery.php displays grid of images from database | 访问 gallery.php 查看图片网格 | gallery.php | Not started |
| 20 | Stage 2 | Gallery | Each image links to corresponding details page | 点击图片是否跳转到 details.php | gallery.php | Not started |
| 21 | Stage 2 | Search | search.php performs full-text search (prepared statements) | 搜索 pet name/description 查看结果 | search.php | Not started |
| 22 | Stage 2 | Owner page | owner.php displays pets by owner via query string (prepared statements) | 访问 owner.php?user_id=X 查看结果 | owner.php | Not started |
| 23 | Stage 3 | JavaScript | Single custom JS file used (assets/js/scripts.js) | 检查是否只有一个自定义 JS 文件 | assets/js/scripts.js | Not started |
| 24 | Stage 3 | JavaScript | No inline JavaScript used | 搜索所有 PHP/HTML 文件中的 onclick/onload 等属性 | *.php, includes/*.inc | Not started |
| 25 | Stage 3 | JavaScript | Image extension validation implemented in JS | 在 add.php 上传非图片文件，检查 JS 是否拦截 | assets/js/scripts.js, add.php | Not started |
| 26 | Stage 3 | JavaScript | Only jpg, jpeg, png, gif, webp accepted | 测试上传 .pdf/.txt 等非图片文件 | assets/js/scripts.js | Not started |
| 27 | Stage 3 | JavaScript | Clicking image opens Bootstrap modal — gallery and details pages | 点击 gallery/details 页面的图片是否弹出模态框 | gallery.php, details.php, assets/js/scripts.js | Not started |
| 28 | Stage 3 | JavaScript | Client-side category filter implemented (JS only) | 在 gallery.php 点击筛选按钮查看 JS 筛选效果 | gallery.php, assets/js/scripts.js | Not started |
| 29 | Stage 3 | JavaScript | Confirmation modal opens from details page before record deletion | 在 details.php 点击 Delete 是否弹出确认框 | details.php, assets/js/scripts.js | Not started |
| 30 | Stage 4 | Authentication | register.php creates users securely (hashed passwords). Upon registration the user is automatically logged in. | 注册新用户后检查数据库 password 字段是否哈希 + 是否自动登录 | register.php | Not started |
| 31 | Stage 4 | Authentication | login.php authenticates users using sessions | 用正确凭据登录，检查 session 是否创建 | login.php | Not started |
| 32 | Stage 4 | Authentication | logout.php destroys session and redirects correctly | 点击登出，检查 session 是否销毁 + 是否跳转首页 | logout.php | Not started |
| 33 | Stage 4 | Sessions | Flash messages implemented using PHP sessions | 测试注册/登录/添加操作后的成功/错误提示 | includes/header.inc, 各处理脚本 | Not started |
| 34 | Stage 4 | Access | Authenticated vs anonymous views handled correctly | 未登录 vs 已登录状态导航栏显示是否不同 | includes/nav.inc | Not started |
| 35 | Stage 5 | Protection | add.php accessible only to logged-in users | 未登录状态下直接访问 add.php，检查是否重定向 | add.php | Not started |
| 36 | Stage 5 | Protection | edit.php accessible only to record owner | 登录为 user A，尝试编辑 user B 的 pet，检查是否拒绝 | edit.php | Not started |
| 37 | Stage 5 | Protection | Delete action accessible only to record owner | 登录为 user A，尝试删除 user B 的 pet，检查是否拒绝 | process_delete.php | Not started |
| 38 | Stage 5 | Protection | Delete removes both DB record and image file | 删除一条记录后检查数据库和文件系统是否都清理了 | process_delete.php | Not started |
| 39 | Stage 5 | Ownership | Owner-only edit/delete buttons enforced server-side | 查看 details.php 源码确认按钮是服务端条件渲染而非 CSS 隐藏 | details.php | Not started |
| 40 | Stage 5 | Files | Uploaded images use server-generated unique filenames (uniqid() or timestamp-based) | 上传同名文件两次，检查是否生成不同文件名 | add.php, edit.php | Not started |
| 41 | Validation | Validation | All HTML pages validate via W3C Validator | 逐页提交到 validator.w3.org 检查 | *.php | Not started |
| 42 | Validation | Validation | CSS validates via W3C CSS Validator | 提交到 jigsaw.w3.org/css-validator | assets/css/styles.css | Not started |
| 43 | Security | Security | assets/images/pets/ added to .gitignore | 检查 .gitignore 是否包含该路径 | .gitignore | Not started |
| 44 | Deploy | Deployment | Site deployed to Coreteaching server | 访问 Coreteaching URL 确认站点可访问 | 全部文件 | Not started |
| 45 | Deploy | Deployment | Permissions set (755 on a3, 777 on images folder) | SSH 到服务器检查文件权限 | a3/, assets/images/pets/ | Not started |
| 46 | Deploy | Deployment | All pages and assets load with no errors | 浏览器逐个页面检查 Console 错误 | 全部文件 | Not started |
| 47 | Security | Security | .htaccess protects against unauthorised viewing of your site | 检查 .htaccess 文件内容是否阻止目录浏览等 | .htaccess | Not started |
| 48 | Git | GitHub | Project located in a3 directory of GitHub repo | 检查 GitHub 仓库结构 | a3/ | Not started |
| 49 | Git | GitHub | Git history shows consistent work over time | 检查 git log 是否有至少 5 个 commit 分布在 4 天以上 | .git | Not started |
| 50 | Git | GitHub | Teacher added as a collaborator | 检查 GitHub repo Settings → Collaborators | GitHub | Not started |
| 51 | Submission | Submission | ZIP named COSC2446_a3_\<studentID\>.zip | 检查 ZIP 文件名格式 | COSC2446_a3_s3976066.zip | Not started |
| 52 | Submission | Submission | ZIP includes all source files and assets | 解压 ZIP 检查文件完整性 | ZIP | Not started |
| 53 | Submission | Documentation | README.md includes live site URL | 检查 README.md 中是否有 Coreteaching URL | README.md | Not started |
| 54 | Demo | Demo | Able to explain PHP, DB queries, sessions, and security | 准备演示：能解释代码关键部分 | 全部 | Not started |

> 注：PDF 中部分编号有重复（#22 在 Stage 2 和 Stage 3 各出现一次，#46 在 Validation 和 GitHub 各出现一次），此表使用连续编号避免混淆，共 54 行对应 PDF 所有检查项。
