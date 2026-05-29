# A3 — Demo 演示脚本

> 准备时间：15 分钟走完 9 个页面 | 覆盖：PHP、SQL、Session、安全

---

## Part A — 关键代码行（7 个文件）

### 1. db_connect.inc — 双环境检测

```php
$isProd = (stripos($server, 'rmit.edu.au') !== false) ||
          (stripos($server, 'jupiter') !== false) ||
          (stripos($server, 'saturn') !== false) ||
          (stripos($server, 'titan') !== false);
if ($isProd) { ... Jacob 5 config ... } else { ... localhost config ... }
```

- **做什么**：根据 `$_SERVER['SERVER_NAME']` 自动切换本地/Jacob 5 数据库连接
- **满足哪个检查项**：#3 — db_connect.inc 支持双环境
- **如果没有它**：部署到 Coreteaching 后所有页面 500 错误，因为连接了错误的数据库
- **可能的提问**："为什么不用两个文件？" → 回答：单一文件避免路径混淆，自动检测减少部署步骤。`stripos` 不区分大小写，避免 Linux/Windows 主机名差异。

### 2. details.php — Prepared Statement + 所有权检查

```php
$stmt = mysqli_prepare($conn, "SELECT * FROM pets WHERE pet_id = ?");
mysqli_stmt_bind_param($stmt, 'i', $petId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pet = mysqli_fetch_assoc($result);

$isOwner = isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] === (int)$pet['user_id'];
```

- **做什么**：用 prepared statement 防 SQL 注入查询单条宠物；服务端判断当前用户是否为宠物主人
- **满足哪个检查项**：#13（所有查询 prepared）、#18（单条记录）、#39（服务端条件渲染）
- **如果没有它**：SQL 注入可删除整个表；非主人可通过直接访问 URL 删除别人的宠物
- **可能的提问**："为什么 `bind_param` 用 `'i'` 而不是 `'s'`？" → 回答：`i` 让 MySQL 把参数当整数处理，类型严格匹配 `pet_id INT` 列，防止隐式类型转换导致的索引失效。

### 3. add.php — 文件上传 + ENUM 验证

```php
$imagePath = uniqid() . '.' . $ext;
$target = 'assets/images/pets/' . $imagePath;
if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) { ... }

$validSpecies = ['Dog', 'Cat', 'Bird', 'Rabbit', 'Other'];
if (!in_array($species, $validSpecies, true)) { ... }
```

- **做什么**：`uniqid()` 生成唯一文件名防止覆盖；`in_array` 严格模式验证 ENUM 值
- **满足哪个检查项**：#40（唯一文件名）、#12（ENUM 精确匹配）
- **如果没有它**：用户上传 `image.jpg` 会覆盖其他人同名的图片；非法 ENUM 值导致 MySQL 报错
- **可能的提问**："为什么客户端和服务器端都验证文件类型？" → 回答：JS 验证是用户体验（即时反馈），PHP 验证是安全防线（任何人都可以绕过 JS 直接 POST）。

### 4. edit.php — UPDATE + 旧图片替换

```php
if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
    if (!empty($pet['image_path'])) {
        $oldFile = 'assets/images/pets/' . $pet['image_path'];
        if (file_exists($oldFile)) unlink($oldFile);
    }
    $imagePath = $newName;
}
```

- **做什么**：新图片上传成功后，`unlink` 删除旧图片文件再更新路径
- **满足哪个检查项**：#36（所有权验证）、#38（图片清理）、stage5-4（编辑删除旧图片）
- **如果没有它**：每次更换图片都会在服务器留下孤儿文件，最终耗尽磁盘空间
- **可能的提问**："如果 `unlink` 失败怎么办？" → 回答：数据库仍然指向旧路径（因为 `$imagePath` 只在 `move_uploaded_file` 成功后更新），系统保持一致状态。

### 5. login.php — password_verify + Session 安全

```php
if ($user && password_verify($password, $user['password'])) {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    header('Location: index.php');
    exit;
} else {
    $error = '用户名或密码错误。';  // 统一错误消息
}
```

- **做什么**：`password_verify` 验证 bcrypt 哈希；`session_regenerate_id(true)` 防 session fixation
- **满足哪个检查项**：#31（登录 + session）、stage4-4（password_verify）
- **如果没有它**：明文密码存储导致数据库泄露时所有用户密码暴露；不 regenerate ID 导致 session fixation 攻击
- **可能的提问**："为什么错误消息不区分'用户名不存在'和'密码错误'？" → 回答：防止用户枚举攻击——攻击者如果知道哪个用户名存在，就完成了暴力破解的一半工作。

### 6. scripts.js — 画廊模态框 + 状态筛选

```js
// 模态框模式
images.forEach(img => {
    img.addEventListener('click', e => {
        e.preventDefault();
        modalImage.src = img.src;
        modalLabel.textContent = img.dataset.petName || '';
        new bootstrap.Modal(modal).show();
    });
});

// 状态筛选模式
select.addEventListener('change', () => {
    const val = select.value;
    cards.forEach(card => {
        card.style.display = (val === 'all' || card.dataset.status === val) ? '' : 'none';
    });
});
```

- **做什么**：`e.preventDefault()` 阻止链接默认跳转，用 Bootstrap Modal API 显示大图；通过 `data-status` 属性实现纯客户端筛选
- **满足哪个检查项**：#27（图片模态框）、#28（客户端筛选）
- **如果没有它**：点击画廊图片跳转到详情页而非弹出模态框；筛选需要刷新页面，用户体验差
- **可能的提问**："为什么用 `data-*` 属性而不是 class 来存储状态？" → 回答：`data-*` 是 HTML5 标准，专门用于存储 JS 数据，不会与 CSS 类名冲突，且可以通过 `dataset` API 直接读取。

### 7. search.php — LIKE 搜索 + Prepared Statement

```php
$like = '%' . $searchTerm . '%';
$stmt = mysqli_prepare($conn,
    "SELECT * FROM pets WHERE name LIKE ? OR description LIKE ? ORDER BY created_at DESC");
mysqli_stmt_bind_param($stmt, 'ss', $like, $like);
```

- **做什么**：`%` 通配符在 PHP 端拼接，两个 `?` 占位符绑定同一个 `$like`，搜索名称和描述
- **满足哪个检查项**：#21（全文搜索 + prepared）
- **如果没有它**：直接拼接到 SQL 中 → `' OR 1=1 --` 可以返回全部数据；不绑定两次会导致只搜索名称或只搜索描述
- **可能的提问**："为什么在 PHP 中拼 `%` 而不在 SQL 中用 `CONCAT('%', ?, '%')`？" → 回答：两种写法等价。PHP 端拼接让 SQL 更简洁易读，且 `$like` 变量可复用于结果计数消息中。

---

## Part B — 15 分钟页面遍历

### 时间分配：每页 ~90 秒

| 分钟 | 页面 | 服务端 | SQL | 客户端交互 |
|------|------|--------|-----|-----------|
| 0:00 | **index.php** | PHP 查询最新 4 条宠物 | `SELECT ... ORDER BY created_at DESC LIMIT 4` | Bootstrap 轮播自动播放，卡片 hover 上浮 |
| 1:30 | **pets.php** | PHP 加载全部宠物到数组 | `SELECT * FROM pets ORDER BY created_at DESC` | 双列卡片响应式布局，点击名称跳转详情 |
| 3:00 | **details.php** | `$_GET['id']` 参数 → prepared SELECT → 主人 JOIN | `SELECT * FROM pets WHERE pet_id=?` + `SELECT * FROM users WHERE user_id=?` | 编辑/删除按钮仅主人可见（`$isOwner` 控制） |
| 4:30 | **gallery.php** | PHP 输出全部宠物卡片 | `SELECT * FROM pets ORDER BY created_at DESC` | JS 状态下拉筛选（0 次服务器请求）+ 点击图片弹出 Bootstrap Modal |
| 6:00 | **search.php** | GET 表单 → `LIKE` 查询 → 卡片网格 | `WHERE name LIKE ? OR description LIKE ?` with `bind_param('ss')` | 输入关键词 → Enter → 结果卡片（无页面刷新） |
| 7:30 | **register.php** | POST 验证 → `password_hash` → INSERT → 自动登录 | `INSERT INTO users (...) VALUES (?,?,...)?` | 表单验证（客户端 JS + 服务器端双重） |
| 9:00 | **login.php** | `password_verify` → `session_regenerate_id` → 重定向 | `SELECT user_id, username, password FROM users WHERE username=?` | 错误消息不区分"用户名不存在"和"密码错误" |
| 10:30 | **add.php** | `$_SESSION` 守卫 → `uniqid()` 文件命名 → INSERT | `INSERT INTO pets (...) VALUES (?,?,...13 params)` | JS 即时图片预览 + 扩展名校验 + 表单回填 |
| 12:00 | **edit.php** | 所有权验证 → 旧图 `unlink` → UPDATE | `UPDATE pets SET name=?...image_path=? WHERE pet_id=?` | 预填表单 + 新图预览 + 旧图显示 |
| 13:30 | **delete** | POST action=delete → 所有权验证 → DELETE → unlink | `DELETE FROM pets WHERE pet_id=?` | 确认模态框 → 提交 → 重定向 pets.php |

### 演示技巧

- **每页先说服务器端在做什么**（"这个页面收到请求后，PHP 做了..."）
- **然后说 SQL**（"它运行了这个查询..."）
- **最后说客户端**（"在浏览器这边，你会看到..."）
- **遇到错误不要慌**——这恰恰是展示你调试能力的机会
- **准备好按需深入**：考官可能打断你问任何一页的细节

---

> 记住 Part B 的 9 步流程。每页用同样的三句话模板：服务端逻辑 → SQL 查询 → 客户端交互。
