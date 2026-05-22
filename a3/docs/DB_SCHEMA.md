# A3 — 数据库 Schema 文档

> 来源：`_requirements/petconnect.sql`

---

## ⚠️ DO NOT RENAME — 所有表名和列名必须完全匹配

以下名称由自动评分器检查，**任何偏差都会导致 Stage 2 扣分**。

### 数据库名
- 本地：`petconnect`
- Jacob 5：`s3976066`（你的学生 ID）

### 表名
- `users`
- `pets`

### users 表列名
`user_id`, `username`, `email`, `password`, `phone`, `location`, `joined_at`

### pets 表列名
`pet_id`, `user_id`, `name`, `species`, `breed`, `age_years`, `age_months`, `gender`, `size`, `description`, `health_info`, `image_path`, `adoption_fee`, `status`, `created_at`

---

## 1. users 表

```sql
CREATE TABLE IF NOT EXISTS users (
    user_id     INT AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(50)   NOT NULL UNIQUE,
    email       VARCHAR(100)  NOT NULL UNIQUE,
    password    CHAR(60)      NOT NULL,
    phone       VARCHAR(20),
    location    VARCHAR(100),
    joined_at   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
```

| 列名 | 类型 | 约束 | 说明 |
|------|------|------|------|
| user_id | INT | PK, AUTO_INCREMENT | 用户唯一 ID |
| username | VARCHAR(50) | NOT NULL, **UNIQUE** | 用户名 |
| email | VARCHAR(100) | NOT NULL, **UNIQUE** | 邮箱 |
| password | CHAR(60) | NOT NULL | bcrypt 哈希（password_hash 输出固定 60 字符） |
| phone | VARCHAR(20) | 可选 | 电话号码 |
| location | VARCHAR(100) | 可选 | 所在地 |
| joined_at | DATETIME | NOT NULL, DEFAULT CURRENT_TIMESTAMP | 注册时间 |

---

## 2. pets 表

```sql
CREATE TABLE IF NOT EXISTS pets (
    pet_id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id         INT           NOT NULL,
    name            VARCHAR(100)  NOT NULL,
    species         ENUM('Dog','Cat','Bird','Rabbit','Other') NOT NULL DEFAULT 'Dog',
    breed           VARCHAR(100),
    age_years       INT,
    age_months      INT,
    gender          ENUM('Male','Female','Unknown') NOT NULL DEFAULT 'Unknown',
    size            ENUM('Small','Medium','Large','Extra Large') NOT NULL DEFAULT 'Medium',
    description     TEXT          NOT NULL,
    health_info     TEXT,
    image_path      VARCHAR(255),
    adoption_fee    DECIMAL(8,2)  NOT NULL DEFAULT 0.00,
    status          ENUM('Available','Pending','Adopted') NOT NULL DEFAULT 'Available',
    created_at      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;
```

| 列名 | 类型 | 约束 | 说明 |
|------|------|------|------|
| pet_id | INT | PK, AUTO_INCREMENT | 宠物唯一 ID |
| user_id | INT | NOT NULL, **FK → users(user_id) ON DELETE CASCADE** | 所属用户 |
| name | VARCHAR(100) | NOT NULL | 宠物名 |
| species | ENUM('Dog','Cat','Bird','Rabbit','Other') | NOT NULL, DEFAULT 'Dog' | 物种 |
| breed | VARCHAR(100) | 可选 | 品种 |
| age_years | INT | 可选 | 年龄（年） |
| age_months | INT | 可选 | 年龄（月） |
| gender | ENUM('Male','Female','Unknown') | NOT NULL, DEFAULT 'Unknown' | 性别 |
| size | ENUM('Small','Medium','Large','Extra Large') | NOT NULL, DEFAULT 'Medium' | 体型 |
| description | TEXT | NOT NULL | 描述 |
| health_info | TEXT | 可选 | 健康信息 |
| image_path | VARCHAR(255) | 可选 | 图片文件路径 |
| adoption_fee | DECIMAL(8,2) | NOT NULL, DEFAULT 0.00 | 领养费用 |
| status | ENUM('Available','Pending','Adopted') | NOT NULL, DEFAULT 'Available' | 领养状态 |
| created_at | DATETIME | NOT NULL, DEFAULT CURRENT_TIMESTAMP | 创建时间 |

### ENUM 值速查

| 列 | 允许值 |
|----|--------|
| species | `'Dog'`, `'Cat'`, `'Bird'`, `'Rabbit'`, `'Other'` |
| gender | `'Male'`, `'Female'`, `'Unknown'` |
| size | `'Small'`, `'Medium'`, `'Large'`, `'Extra Large'` |
| status | `'Available'`, `'Pending'`, `'Adopted'` |

### 外键行为
- `user_id` → `users(user_id)` **ON DELETE CASCADE**
- 删除用户时，其所有宠物记录自动删除

---

## 3. 种子用户（5 个）

所有用户密码 = `password123`（bcrypt 哈希值相同）

| user_id | username | email | password (hash) | phone | location |
|---------|----------|-------|-----------------|-------|----------|
| 1 | sarah_animal_lover | sarah@example.com | $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi | 555-0101 | Melbourne, VIC |
| 2 | john_foster | john@example.com | $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi | 555-0102 | Sydney, NSW |
| 3 | emma_petcare | emma@example.com | $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi | 555-0103 | Brisbane, QLD |
| 4 | mike_rescuer | mike@example.com | $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi | 555-0104 | Perth, WA |
| 5 | lisa_shelter | lisa@example.com | $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi | 555-0105 | Adelaide, SA |

---

## 4. 种子宠物（8 个）

| pet_id | user_id | name | species | breed | age | gender | size | image_path | adoption_fee | status |
|--------|---------|------|---------|-------|-----|--------|------|------------|--------------|--------|
| 1 | 1 | Buddy | Dog | Golden Retriever | 3y 6m | Male | Large | 1.jpg | 250.00 | Available |
| 2 | 1 | Whiskers | Cat | Tabby | 2y 0m | Female | Small | 2.jpg | 150.00 | Available |
| 3 | 2 | Max | Dog | Labrador Mix | 5y 0m | Male | Large | 3.jpg | 200.00 | Available |
| 4 | 2 | Luna | Cat | Siamese | 1y 8m | Female | Medium | 4.jpg | 180.00 | Pending |
| 5 | 3 | Charlie | Bird | Cockatiel | 2y 0m | Male | Small | 5.jpg | 120.00 | Available |
| 6 | 3 | Bella | Dog | Beagle | 4y 3m | Female | Medium | 6.jpg | 220.00 | Available |
| 7 | 4 | Oliver | Cat | Persian | 3y 0m | Male | Medium | 7.jpg | 200.00 | Available |
| 8 | 5 | Rocky | Dog | German Shepherd | 6y 0m | Male | Extra Large | 8.jpg | 180.00 | Available |

---

## 5. image_path 命名规则

> ⚠️ **关键**：数据库中的 image_path 值为 `"1.jpg"` 到 `"8.jpg"`。
> 种子图片必须按此命名放在 `assets/images/pets/` 目录下。

| 宠物名 | image_path | 对应 A1 图片文件 |
|--------|------------|------------------|
| Buddy | 1.jpg | Buddy.jpg → 需重命名为 1.jpg |
| Whiskers | 2.jpg | Whiskers.jpg → 需重命名为 2.jpg |
| Max | 3.jpg | Max.jpg → 需重命名为 3.jpg |
| Luna | 4.jpg | Luna.jpg → 需重命名为 4.jpg |
| Charlie | 5.jpg | Charlie.jpg → 需重命名为 5.jpg |
| Bella | 6.jpg | Bella.jpg → 需重命名为 6.jpg |
| Oliver | 7.jpg | Oliver.jpg → 需重命名为 7.jpg |
| Rocky | 8.jpg | Rocky.jpg → 需重命名为 8.jpg |

> **不要修改 petconnect.sql 中的 image_path 值** — 自动评分器期望这些精确值。
