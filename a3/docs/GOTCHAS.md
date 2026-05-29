# A3 — 常见丢分陷阱

> 手 curated 的 A3 特定陷阱列表，非通用 PHP 建议。每一条都可能让你损失分数。

---

## 文件命名

| 陷阱 | 说明 |
|------|------|
| `process_add.php` 是**可选**的，`add.php` 是**强制**的 | PDF p.9 明确：`process_add.php` — "Not mandatory. Adding a new record can be handled by add.php" |
| 文件名必须**全小写** | `login.php` 不是 `Login.php` — 自动评分器精确匹配 |
| JS 文件：`scripts.js`（复数） | 不注意容易写成 `script.js` |
| CSS 文件：`styles.css`（复数） | 不注意容易写成 `style.css`（A1 旧名） |
| 种子图片：`1.jpg` 到 `8.jpg` | 不是 `Buddy.jpg`、`Whiskers.jpg` 等描述性名称 |

---

## 数据库

| 陷阱 | 说明 |
|------|------|
| Jacob 5 数据库名 = 学生 ID `s3976066`，**不是** `petconnect` | petconnect 只是本地开发用 |
| 表名/列名不能改 | 自动评分器精确检查 `users`、`pets` 及其所有列名 |
| `password` 列是 `CHAR(60)` | bcrypt 输出固定 60 字符——不要用 `VARCHAR(255)` |
| `adoption_fee` 是 `DECIMAL(8,2)` | 不要用 `FLOAT` 或 `INT` |
| `image_path` 种子值为 `'1.jpg'`...`'8.jpg'` | 不要改成其他值 |
| `species` ENUM 第 5 个值是 `'Other'` | 不是 `'Others'`，不是 `'Other Animal'` |
| `size` ENUM 第 4 个值是 `'Extra Large'` | 中间有空格，大小写要精确 |
| FK: `ON DELETE CASCADE` | 删除用户 → 其宠物自动删除，不要忘记 |

---

## 数据库连接（db_connect.inc）

| 陷阱 | 说明 |
|------|------|
| 必须支持**双环境**切换 | localhost（XAMPP）+ Jacob 5（talsprddb02.int.its.rmit.edu.au） |
| 本地数据库名：`petconnect` | Jacob 5 数据库名：`s3976066` |
| 使用 MySQLi procedural | 不是 OOP 风格，不是 PDO |
| `session_start()` 放在 db_connect.inc 顶部 | 在任何输出之前——确保所有页面都 include 它 |

---

## Sessions & Auth

| 陷阱 | 说明 |
|------|------|
| `session_start()` 必须在**任何 HTML 输出之前** | 最好放在 `db_connect.inc` 最顶部 |
| 登录成功后调用 `session_regenerate_id(true)` | 防止 session fixation 攻击 |
| 注册成功后**自动登录**用户 | PDF 明确要求："Upon registration the user is automatically logged in" |
| Flash 消息用 `$_SESSION['flash']` | 读一次后 `unset($_SESSION['flash'])` — 刷新页面不能重复显示 |
| `password_hash()` 用于注册 | 默认 bcrypt |
| `password_verify()` 用于登录 | 不要自己写比较逻辑 |

---

## 权限 & 所有权

| 陷阱 | 说明 |
|------|------|
| Edit/Delete 按钮**服务端条件渲染** | 不是 CSS `display:none` / JS 隐藏 — 必须 PHP `if` 判断后才输出 HTML |
| 按钮逻辑：`if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $pet['user_id'])` | 两个条件都要检查 |
| 编辑前验证所有权 | 加载记录时 `WHERE pet_id = ? AND user_id = ?` |
| 删除前验证所有权 | 同理，先查 `user_id` 再删 |
| 删除操作同时清理：DB 记录 + 图片文件 | `unlink()` 删除文件 + `DELETE` 删除记录 |
| 编辑上传新图时删除旧图 | 先读旧 `image_path`，`unlink()` 旧文件，再存新文件 |

---

## 文件上传

| 陷阱 | 说明 |
|------|------|
| **永远不要**信任原始文件名 | 用 `uniqid()` 或 `time() . '_' . basename($file['name'])` |
| 服务器端也要验证扩展名 | JS 验证只是 UX，PHP 端必须再验一次 |
| 允许的扩展名：`jpg, jpeg, png, gif, webp` | 与 JS 列表一致 |
| 上传路径：`assets/images/pets/` | 注意末尾斜杠 |

---

## 安全

| 陷阱 | 说明 |
|------|------|
| `.htaccess` 阻止直接访问 `*.inc` 文件 | `Deny from all` 或等价配置 |
| `.gitignore`：`assets/images/pets/*` | 但保留 `assets/images/pets/.gitkeep` 确保目录存在 |
| 所有用户输入查询都用 prepared statements | 搜索、详情、编辑、删除、登录、注册 — 无一例外 |
| 服务器权限：`a3/` → 755，`assets/images/pets/` → 777 | Coreteaching 部署后检查 |

---

## 前端

| 陷阱 | 说明 |
|------|------|
| 搜索图标用 **Material Icons** | 不是 Bootstrap Icons，不是 emoji |
| 页面 `<title>` 必须**每页唯一** | 自动评分器检查 |
| Footer 需要**渐变背景 + 你的名字** | 名字要写 `s3976066` |
| Navbar 中的 PetConnect logo 是**链接** | 指向首页 `index.php` |
| 字体：Headings = Poppins，Body = Inter | 不要用其他字体 |
| 禁止内联 JS | `onclick`、`onsubmit`、`onchange` 等属性一律不能在 HTML 中出现 |
| JS 必须集中在 `assets/js/scripts.js` | 单个文件，不能分散在页面底部 `<script>` 块 |
| 颜色变量用 `#6366f1` 等精确值 | PDF 定义了完整的品牌色系统 |

---

## Gallery 页面

| 陷阱 | 说明 |
|------|------|
| 筛选用 `data-status` 属性 | 不是 `data-filter`、`data-category` |
| 筛选是**纯 JS**（客户端） | 不是 PHP 查询参数或页面刷新 |
| 模态框用 Bootstrap Modal | 不是自定义弹窗 |
| 图片可点击并触发模态框 | 需要事件委托（因为图片是动态 PHP 输出的） |

---

## 部署 & 提交

| 陷阱 | 说明 |
|------|------|
| `search.php` 用 `LIKE` | 除非添加 FULLTEXT 索引，否则不要用 `MATCH AGAINST` |
| ZIP 命名：`COSC2446_a3_s3976066.zip` | 精确格式 |
| README.md 必须包含**线上 URL** | Coreteaching 部署地址 |
| Git：至少 5 个 commit，至少 4 个不同日期 | 单日超过 50% 工作 → 总扣 50% |
| 教师必须添加为 GitHub collaborator | tanyarmit |

---

## 演示

| 陷阱 | 说明 |
|------|------|
| 必须能解释自己的代码 | 无法解释 → 最高 50% (Pass) |
| 准备回答：PHP、DB 查询、Sessions、安全 | 四个方向都可能被问到 |
