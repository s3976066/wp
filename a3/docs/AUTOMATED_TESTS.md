# A3 — 自动化测试清单

> 来源：`_requirements/AT3_S123456.html`（100% 评分样本报告）
> **这是最重要的文档** — 它精确展示了自动评分器检查的内容

---

## 评分概览

| 指标 | 分值 |
|------|------|
| Automated Tests (Stages 1-5) | 70 / 70 |
| Manual Grading | 30 / 30 |
| **Total** | **100 / 100** |
| Grade | HD (100%) |

---

## Stage 1: PHP Structure（满分 20 分）

| test_id | stage | test_title | max_points | what_passes | file_to_check |
|---------|-------|------------|------------|-------------|---------------|
| stage1-0 | Stage 1 | Main pages exist | 1.5 | All required files exist (index.php, pets.php, gallery.php, details.php, owner.php, add.php, edit.php) | index.php, pets.php, gallery.php, details.php, owner.php, add.php, edit.php |
| stage1-1 | Stage 1 | Authentication pages exist | 1.0 | register.php, login.php, logout.php exist | register.php, login.php, logout.php |
| stage1-2 | Stage 1 | CRUD pages exist and delete functionality | 1.5 | add.php, edit.php, and delete functionality exist | add.php, edit.php, process_delete.php |
| stage1-3 | Stage 1 | Required include files exist | 1.5 | All required include files exist (includes/header.inc, includes/footer.inc, includes/nav.inc) | includes/header.inc, includes/footer.inc, includes/nav.inc |
| stage1-4 | Stage 1 | Semantic HTML5 tags used (header, nav, main, footer) | 2.0 | All required semantic tags found | *.php, includes/*.inc |
| stage1-5 | Stage 1 | index.php renders valid HTML | 1.5 | Valid HTML | index.php |
| stage1-6 | Stage 1 | pets.php renders valid HTML | 1.5 | Valid HTML | pets.php |
| stage1-7 | Stage 1 | gallery.php renders valid HTML | 1.5 | Valid HTML | gallery.php |
| stage1-8 | Stage 1 | add.php renders valid HTML | 1.5 | Valid HTML | add.php |
| stage1-9 | Stage 1 | details.php renders valid HTML | 1.5 | Valid HTML | details.php |
| stage1-10 | Stage 1 | owner.php renders valid HTML | 1.5 | Valid HTML | owner.php |
| stage1-11 | Stage 1 | search.php renders valid HTML | 1.5 | Valid HTML | search.php |
| stage1-12 | Stage 1 | register.php renders valid HTML | 1.0 | Valid HTML | register.php |
| stage1-13 | Stage 1 | login.php renders valid HTML | 1.0 | Valid HTML | login.php |

---

## Stage 2: Database Structure（满分 15 分）

| test_id | stage | test_title | max_points | what_passes | file_to_check |
|---------|-------|------------|------------|-------------|---------------|
| stage2-0 | Stage 2 | db_connect.inc file contains MySQLi connection code with correct structure | 2.0 | MySQLi connection present | includes/db_connect.inc |
| stage2-1 | Stage 2 | Registration inserts user with correct fields | 3.0 | User created with correct fields | register.php |
| stage2-2 | Stage 2 | Add record inserts record with correct fields | 3.0 | Record created (name, species, gender, size, description, user_id linked) | add.php |
| stage2-3 | Stage 2 | Edit record updates correct fields | 4.0 | Fields updated (name, species, size, description) | edit.php |
| stage2-4 | Stage 2 | details.php has link to owner.php and owner.php displays correct data | 3.0 | Full functionality working (owner link found on details page; pet displayed on owner page; username displayed on owner page; cards used for display) | details.php, owner.php |

---

## Stage 3: JavaScript & Responsive Design（满分 10 分）

| test_id | stage | test_title | max_points | what_passes | file_to_check |
|---------|-------|------------|------------|-------------|---------------|
| stage3-0 | Stage 3 | File extension validation exists in JavaScript | 0.75 | JS validation found | assets/js/scripts.js |
| stage3-1 | Stage 3 | Bootstrap modal exists in gallery page | 0.5 | Modal found | gallery.php |
| stage3-2 | Stage 3 | Gallery images are clickable and trigger modal | 0.5 | Images trigger modal | gallery.php, assets/js/scripts.js |
| stage3-3 | Stage 3 | Modal closes correctly | 0.5 | Successfully closes with button | gallery.php, assets/js/scripts.js |
| stage3-4 | Stage 3 | Delete confirmation modal on details.php | 1.0 | Confirmation modal found | details.php, assets/js/scripts.js |
| stage3-5 | Stage 3 | Bootstrap grid system is used | 1.0 | Grid classes found | *.php |
| stage3-6 | Stage 3 | Mobile viewport works correctly | 1.0 | Responsive at mobile width | *.php, assets/css/styles.css |
| stage3-7 | Stage 3 | Tablet viewport works correctly | 1.0 | Responsive at tablet width | *.php, assets/css/styles.css |
| stage3-8 | Stage 3 | Desktop viewport works correctly | 1.0 | Responsive at desktop width | *.php, assets/css/styles.css |
| stage3-9 | Stage 3 | Filter by "Available" shows correct records | 0.5 | Shows correct records | gallery.php, assets/js/scripts.js |
| stage3-10 | Stage 3 | Filter by "Pending" shows correct records | 0.5 | Shows correct records | gallery.php, assets/js/scripts.js |
| stage3-11 | Stage 3 | Filter by "Adopted" shows correct records | 0.5 | Shows correct records | gallery.php, assets/js/scripts.js |
| stage3-12 | Stage 3 | "Show All" resets filter | 0.5 | Resets filter correctly | gallery.php, assets/js/scripts.js |
| stage3-13 | Stage 3 | Image preview functionality exists | 0.75 | Found (custom JavaScript file loaded (assets/js/scripts.js)) | assets/js/scripts.js, add.php |
| stage3-14 | Stage 3 | JavaScript loads without errors | 0 (penalty: -1 if fails) | JavaScript loads without errors | assets/js/scripts.js |
| stage3-15 | Stage 3 | All JavaScript in single file scripts.js | 0 (penalty: -1 if fails) | All JavaScript in single file scripts.js | assets/js/scripts.js |
| stage3-16 | Stage 3 | No inline JavaScript event handlers used | 0 (penalty: -2 if found) | No inline JavaScript found | *.php, includes/*.inc |

---

## Stage 4: Authentication & Sessions（满分 10 分）

| test_id | stage | test_title | max_points | what_passes | file_to_check |
|---------|-------|------------|------------|-------------|---------------|
| stage4-0 | Stage 4 | Registration form processes and creates user account | 1.5 | User account created successfully | register.php |
| stage4-1 | Stage 4 | Password is encrypted using password_hash | 1.5 | password_hash() used correctly | register.php |
| stage4-2 | Stage 4 | Login page has required form fields | 0.75 | All required fields present | login.php |
| stage4-3 | Stage 4 | Login creates session | 1.25 | Session created successfully (indicators: username displayed, logout button, welcome message, left login page) | login.php |
| stage4-4 | Stage 4 | Login uses password_verify for authentication | 1.0 | password_verify used | login.php |
| stage4-5 | Stage 4 | Navbar shows different content for logged-in vs logged-out users | 1.25 | Dynamic navigation works correctly (login/register shown when logged out; no logged-in elements when logged out; login/register hidden when logged in; logged-in elements shown when logged in) | includes/nav.inc |
| stage4-6 | Stage 4 | Logout functionality destroys session | 1.25 | Session destroyed and redirects correctly | logout.php |
| stage4-7 | Stage 4 | Flash messages use $_SESSION | 0.75 | $_SESSION used for flash messages | includes/header.inc, 各处理脚本 |
| stage4-8 | Stage 4 | Flash messages are displayed and cleared | 0.75 | Messages displayed and cleared (error message displayed, message cleared on reload) | includes/header.inc |

---

## Stage 5: Authorization & Protected Routes（满分 15 分）

| test_id | stage | test_title | max_points | what_passes | file_to_check |
|---------|-------|------------|------------|-------------|---------------|
| stage5-0 | Stage 5 | add.php requires login (redirects if not logged in) | 2.0 | Requires login (redirects or shows access denied) | add.php |
| stage5-1 | Stage 5 | add.php has session check in code | 1.0 | Session check found in code | add.php |
| stage5-2 | Stage 5 | Edit functionality verifies record owner | 2.0 | Owner verification implemented (found in: edit.php, processing script, SQL query with user_id) | edit.php |
| stage5-3 | Stage 5 | edit.php uses prepared statements | 1.0 | Prepared statements used | edit.php |
| stage5-4 | Stage 5 | Edit functionality removes old image file when uploading new one | 0.75 | Old image deletion implemented | edit.php |
| stage5-5 | Stage 5 | Edit/Delete buttons only show for owner on details.php | 0.75 | Edit/Delete buttons conditional on ownership | details.php |
| stage5-6 | Stage 5 | Delete functionality verifies record owner | 2.0 | Owner verification implemented (found in: process_delete.php) | process_delete.php |
| stage5-7 | Stage 5 | Delete functionality removes associated image file | 1.5 | Image file deletion implemented (found in: process_delete.php) | process_delete.php |
| stage5-8 | Stage 5 | Delete functionality uses prepared statements | 1.0 | Prepared statements used (found in: process_delete.php) | process_delete.php |
| stage5-9 | Stage 5 | owner.php filters records by user ID | 2.0 | Correctly filters records by user ID with prepared statements | owner.php |
| stage5-10 | Stage 5 | owner.php displays records in card format | 1.0 | Cards used for display | owner.php |

---

## Total Automated: 70 分

| Stage | 分值 |
|-------|------|
| Stage 1: PHP Structure | 20.0 |
| Stage 2: Database Structure | 15.0 |
| Stage 3: JavaScript & Responsive Design | 10.0 |
| Stage 4: Authentication & Sessions | 10.0 |
| Stage 5: Authorization & Protected Routes | 15.0 |
| **Total** | **70.0** |

---

## Penalty Tests（负分项）

以下测试 0 分为通过，不通过则扣分：

| test_id | 条件 | 惩罚 |
|---------|------|------|
| stage3-14 | JavaScript 加载有错误 | -1 |
| stage3-15 | JS 未集中在单文件 | -1 |
| stage3-16 | 存在内联 JavaScript | -2 |

---

## Manual Grading Section（共 30 分）

### Page Compliance with Instructions（10 分）

| item_id | 要求 | max_points |
|---------|------|------------|
| page-index | index.php matches provided screenshot | 1 |
| page-pets | pets.php matches provided screenshot | 1 |
| page-gallery | gallery.php matches provided screenshot | 1 |
| page-add | add.php matches provided screenshot | 1 |
| page-details | details.php matches provided screenshot | 1 |
| page-owner | owner.php matches provided screenshot | 1 |
| page-edit | edit.php matches provided screenshot | 1 |
| page-search | search.php matches provided screenshot | 1 |
| page-register | register.php matches provided screenshot | 1 |
| page-login | login.php matches provided screenshot | 1 |

### Site Deployment（10 分）

| item_id | 要求 | max_points |
|---------|------|------------|
| deploy-site | The site is deployed to Coreteaching, data to Jacob5 | 6.5 |
| deploy-readme | The URL provided in the README.md file | 0.5 |
| deploy-images | Images displayed properly | 3.0 |

### GitHub and Project History（10 分）

| item_id | 要求 | max_points |
|---------|------|------------|
| git-vcs | The code is under version control | 3.0 |
| git-commits | Multiple commits on at least 5 separate days | 3.0 |
| git-messages | Meaningful commit messages | 4.0 |

### Manual Grading Summary

| Category | 分值 |
|----------|------|
| Page Compliance | 10 |
| Site Deployment | 10 |
| GitHub and Project History | 10 |
| **Total Manual** | **30** |
