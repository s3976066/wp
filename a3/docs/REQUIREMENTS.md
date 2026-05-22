# COSC2446 A3 — 需求文档

> 来源：`_requirements/COSC2446 Web Programming Assessment3.pdf`（以下为原文引用/结构摘录）

---

## 1. 任务概览与 A3 vs A2 的区别

### 任务概述（PDF 第 1-2 页引用）

> "This Extended Project aims to develop further PetConnect, a pet adoption platform using PHP, MySQL, and Bootstrap 5. The application enables owners to register, authenticate, and manage pet listings with image uploads. Both authenticated users and anonymous visitors will interact with various dynamic features, including a gallery with modal previews, full-text search, filtering by category, and secure CRUD operations."

- **权重**：35%
- **截止日期**：2026年5月29日 COB (5:30pm)
- **形式**：个人作业 + 一对一演示
- **预计工时**：40-50 小时

### A3 相比 A2 的新增内容

| 方面 | A2 (静态/前端为主) | A3 (全栈动态) |
|------|---------------------|---------------|
| 后端语言 | 无/HTML only | PHP（原生、过程式） |
| 数据库 | 无 | MySQL（Jacob 5 + 本地 XAMPP） |
| 用户认证 | 无 | 注册/登录/登出 + session |
| 权限控制 | 无 | 登录保护 + 所有权验证 |
| CRUD | 无 | 完整增删改查 |
| 图片上传 | 无 | 文件上传 + 唯一命名 + 清理 |
| 搜索 | 无 | 全文搜索 + 分类筛选 |
| 模态框 | 可能无 | Bootstrap Modal（图库 + 删除确认） |
| 部署 | 无 | Coreteaching + Jacob 5 |
| 演示 | 可能无 | 一对一代码讲解 |

---

## 2. 52 项评估清单（PDF 第 3-5 页）

### Stage 1 — PHP Structure & Includes（项目 #1-#10）

| # | 领域 | 要求 |
|---|------|------|
| 1 | Structure | All required files exist in the a3 directory |
| 2 | Structure | Required folders exist (assets, includes, assets/images/pets) |
| 3 | Includes | includes/db_connect.inc supports localhost and Jacob 5 |
| 4 | Includes | header.inc, nav.inc, and footer.inc included on all pages |
| 5 | Metadata | All pages have unique and meaningful `<title>` tags |
| 6 | Semantics | Semantic HTML used (header, nav, main, footer) |
| 7 | Layout | Responsive layout implemented with Bootstrap 5 |
| 8 | Navigation | Navbar includes PetConnect logo linking to home |
| 9 | Navigation | Navbar includes a link to the search page with a Material Icon |
| 10 | Layout | Footer includes your name and gradient background |

### Stage 2 — Database Structure & CRUD Operations（项目 #11-#22）

| # | 领域 | 要求 |
|---|------|------|
| 11 | Database | Database created on Jacob 5 using correct schema |
| 12 | Database | Table and column names match provided schema exactly |
| 13 | Security | All database queries use prepared statements |
| 14 | Home Page | index.php shows carousel of latest images from DB |
| 15 | Home Page | index.php shows grid of latest 4 pets from DB |
| 16 | Pets Page | pets.php uses two-column layout with DB records |
| 17 | Pets Page | Pet names link to details.php |
| 18 | Details Page | details.php displays a single record via query string (prepared statements) |
| 19 | Gallery | gallery.php displays grid of images from database |
| 20 | Gallery | Each image links to corresponding details page |
| 21 | Search | search.php performs full-text search (prepared statements) |
| 22 | Owner page | owner.php displays pets by owner via query string (prepared statements) |

### Stage 3 — JavaScript, Modals & Client-Side Logic（项目 #22-#28）

| # | 领域 | 要求 |
|---|------|------|
| 22 | JavaScript | Single custom JS file used (assets/js/scripts.js) |
| 23 | JavaScript | No inline JavaScript used |
| 24 | JavaScript | Image extension validation implemented in JS |
| 25 | JavaScript | Only jpg, jpeg, png, gif, webp accepted |
| 26 | JavaScript | Clicking image opens Bootstrap modal — gallery and details pages |
| 27 | JavaScript | Client-side category filter implemented (JS only) |
| 28 | JavaScript | Confirmation modal opens from details page before record deletion |

### Stage 4 — Authentication & Sessions（项目 #29-#33）

| # | 领域 | 要求 |
|---|------|------|
| 29 | Authentication | register.php creates users securely (hashed passwords). Upon registration the user is automatically logged in. |
| 30 | Authentication | login.php authenticates users using sessions |
| 31 | Authentication | logout.php destroys session and redirects correctly |
| 32 | Sessions | Flash messages implemented using PHP sessions |
| 33 | Access | Authenticated vs anonymous views handled correctly |

### Stage 5 — Authorization & Protected Routes（项目 #34-#39）

| # | 领域 | 要求 |
|---|------|------|
| 34 | Protection | add.php accessible only to logged-in users |
| 35 | Protection | edit.php accessible only to record owner |
| 36 | Protection | Delete action accessible only to record owner |
| 37 | Protection | Delete removes both DB record and image file |
| 38 | Ownership | Owner-only edit/delete buttons enforced server-side |
| 39 | Files | Uploaded images use server-generated unique filenames (e.g. uniqid() or timestamp-based naming) |

### Validation, Deployment & Security（项目 #40-#46）

| # | 领域 | 要求 |
|---|------|------|
| 40 | Validation | All HTML pages validate via W3C Validator |
| 41 | Validation | CSS validates via W3C CSS Validator |
| 42 | Security | assets/images/pets/ added to .gitignore |
| 43 | Deployment | Site deployed to Coreteaching server |
| 44 | Deployment | Permissions set (755 on a3, 777 on images folder) |
| 45 | Deployment | All pages and assets load with no errors |
| 46 | Security | .htaccess protects against unauthorised viewing of your site |

### GitHub, Submission & Demo（项目 #46-#52）

| # | 领域 | 要求 |
|---|------|------|
| 46 | Git | Project located in a3 directory of GitHub repo |
| 47 | Git | Git history shows consistent work over time |
| 48 | Git | Teacher added as a collaborator |
| 49 | Submission | ZIP named COSC2446_a3_\<studentID\>.zip |
| 50 | Submission | ZIP includes all source files and assets |
| 51 | Documentation | README.md includes live site URL |
| 52 | Demo | Able to explain PHP, DB queries, sessions, and security |

---

## 3. 文件结构（PDF 第 9 页/文档页码第 8 页）

```
a3/
├── assets/                     # Static assets (images, CSS, JS)
│   ├── images/
│   │   └── pets/               # Pet photos
│   ├── css/
│   │   └── styles.css          # CSS
│   └── js/
│       └── scripts.js          # JavaScript
├── includes/                   # Include files (header, footer, nav, db connection)
│   ├── db_connect.inc
│   ├── header.inc
│   ├── footer.inc
│   └── nav.inc
├── index.php                   # Homepage with carousel and pet listings
├── details.php                 # Pet details page
├── gallery.php                 # Gallery view of all pets
├── pets.php                    # Browse pets by species
├── register.php                # User registration
├── login.php                   # User login
├── logout.php                  # User logout
├── owner.php                   # Displays all pets by the same owner
├── add.php                     # Add new pet form. Requires login
├── process_add.php             # Add pet handler. Not mandatory.
├── edit.php                    # Editing pet's record. Accessible by the logged in owner
└── search.php                  # Shows search results
```

---

## 4. Pages & Features 详细表（PDF 第 9-10 页）

| 文件 | 功能需求 |
|------|----------|
| **index.php** | Bootstrap carousel of the 4 latest images; Responsive grid of 4 latest pets |
| **register.php** + processing | Form & script using password_hash() encryption; Flash feedback via $_SESSION; Immediately log in user upon successful registration |
| **login.php** + processing | Form & script using password_hash() encryption; Flash feedback via $_SESSION |
| **logout.php** | Destroys session → redirects to the home page |
| **add.php** + processing | Protected (login only); Form & adding a record script. Use prepared statements; Server-side checks, record creation and image upload |
| **edit.php** + processing | Uploaded image files must use unique filenames to prevent overwriting existing files (uniqid() or timestamp-based naming); Protected & owner-only; Replaces old image file on upload; Cleans up old file |
| **pets.php** | Two-column layout: left - banner image, right - table; Table with records from the database; Custom link colours; Name is a hyperlink to details.php |
| **gallery.php** | Grid of all pet images; Names of the images come from the database; Client-side filter by category (JavaScript only); Images open in a Bootstrap modal on click |
| **details.php** | Detail page with pet's image. Use prepared statements; Edit/Delete buttons for owner only. Protected; Deletion requires confirmation, removes associated image file and the DB record |
| **owner.php** | Shows all their pets via cards. Use prepared statements; No login required to view the page; A link to the owner with a query string is displayed on the details.php page |
| **search.php** | Full-text search on title/description; Prepared statements |

---

## 5. Client-Side JavaScript 需求（PDF 第 10 页）

> 原文引用：
> - "Image-extension validation on for the `<input type="file" name="image">` field: only jpg, jpeg, png, gif, webp."
> - "Image preview before the selected file is uploaded on the add.php page."
> - "Modals for full-size images gallery.php (no inline JS — use assets/js/scripts.js)."
> - "Category filter on gallery.php: pure JavaScript show/hide. Use data-status field."
> - "Deletion confirmation on details.php when owner click the 'Delete' button."

汇总：
1. **文件扩展名校验** — `<input type="file" name="image">` 只允许 jpg, jpeg, png, gif, webp
2. **图片预览** — add.php 页面上传前预览
3. **图库模态框** — gallery.php 点击图片打开 Bootstrap Modal（禁止内联 JS）
4. **分类筛选** — gallery.php 纯 JS show/hide，使用 `data-status` 属性
5. **删除确认** — details.php 所有者点击 Delete 按钮时弹出确认模态框

---

## 6. Security & Best Practices（PDF 第 10-11 页）

1. **Prepared Statements 全覆盅** — 所有涉及用户输入的查询（插入记录、搜索、详情页、登录、编辑、删除）必须使用 prepared statements 防 SQL 注入
2. **Flash Messages** — 所有用户交互（success/error）必须使用 PHP sessions
3. **文件系统卫生** — 新上传创建新文件，编辑/删除时清理旧图片
4. **唯一文件名** — 不得信任原始上传文件名，必须服务器端生成唯一名（uniqid() 或时间戳）
5. **.gitignore** — assets/images/pets/ 加入 .gitignore 防止同步问题

---

## 7. Submission Instructions（PDF 第 11 页）

1. Push 到私有 Git 仓库，要求清晰的 commit 记录
2. 部署到 Coreteaching web servers，确保所有资源加载正常
3. 使用 .htaccess 文件防止未授权访问
4. 通过 Canvas 提交 ZIP 包：
   - 包含所有文件
   - README.md 中包含线上站点 URL

---

## 8. Deployment Requirements（PDF 第 6 页）

- **Web 服务器**：Coreteaching — Jupiter/Saturn/Titan (jupiter.csit.rmit.edu.au / saturn.csit.rmit.edu.au / titan.csit.rmit.edu.au)
- **数据库**：Jacob 5，数据库名称必须为学生 ID（如 s3976066）
- **文件权限**：a3 目录 755，assets/images/pets/ 目录 777
- **Git 要求**：
  - 仓库位于 `wp` GitHub repo 的 `a3` 目录
  - 原始 clone 来自 https://github.com/tanyarmit/wp.git
  - 添加教师为 collaborator
  - 至少 5 次 commit，分布在至少 4 个不同日期
  - 任何单日不能超过 50% 的工作量
  - **惩罚**：若少于 5 次 commit 或所有/50% commit 在同一天，扣总分 50%

---

## 9. 参考截图列表

| PDF 页码 | 截图内容 |
|-----------|----------|
| 11 | Home page, user is not logged in |
| 11 | Home page, the user has just logged in |
| 12 | Home page, user is logged in |
| 12 | Pets page |
| 13 | Gallery page |
| 13 | Gallery page, with modal |
| 14 | Gallery page with a filter applied |
| 14 | Details page, user is not logged in or not the owner of the record |
| 15 | Owner page |
| 15 | Add page, the user is logged in |
| 16 | Details page, the user is logged in and the owner of the record |
| 16 | Edit page, the user is logged in and the owner of the record |
| 17 | Details page, the user is logged in and the owner of the record. Delete button clicked |
| 17 | Search page |
| 18 | Register page |
| 18 | Login page |

---

## 10. 其他重要约束（PDF 各处摘录）

- **颜色系统**（PDF 第 8 页）：
  - Primary: #6366f1 (Indigo), Primary Dark: #4f46e5, Primary Light: #818cf8
  - Secondary: #ec4899 (Pink), Secondary Dark: #db2777
  - Accent Orange: #f59e0b, Accent Green: #10b981, Accent Purple: #8b5cf6
  - Text Dark: #1f2937, Text Light: #6b7280, Background Light: #f9fafb
- **字体**：
  - Headings: Poppins (weights: 400, 500, 600, 700, 800)
  - Body: Inter (weights: 400, 500, 600, 700)
  - Icons: Material Icons
- **命名规范**：文件名必须严格遵循规定，否则丢分
- **数据库连接**：单连接文件 includes/db_connect.inc，需适配 localhost 和 Jacob 5 双环境
- **AI 工具**：允许使用，但必须声明；演示时必须能清晰解释代码
- **演示要求**：若无法清晰理解自己的代码，最高不超过 50% (Pass)
