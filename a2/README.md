# PetConnect – AT2 Dynamic Website

**Student:** Yizhao Zheng  
**Student ID:** s3976066  
**Course:** COSC2446 Web Programming  
**Assessment:** Assignment 2 – Dynamic Website & Demonstration  

---

## Live Site URL

http://jupiter.csit.rmit.edu.au/~s3976066/a2/

*(Update with your actual Coreteaching server URL after deployment)*

---

## Technologies

- PHP (procedural, MySQLi prepared statements)
- MySQL (localhost for dev / Jacob5: `talsprddb02.int.its.rmit.edu.au` for production)
- Bootstrap 5.3
- Vanilla JavaScript (no frameworks)
- Google Fonts: Poppins + Inter
- Material Icons

---

## Pages

| File | Description |
|------|-------------|
| `index.php` | Homepage – Bootstrap carousel + 4 latest pets from DB |
| `pets.php` | Browse all pets – two-column layout, table from DB |
| `gallery.php` | Image gallery – modal viewer + dropdown status filter |
| `details.php` | Single pet details – fetched by `?id=N` |
| `add.php` | Add new pet – form with image upload + server processing |

---

## Setup (Local XAMPP)

1. Clone into `htdocs/a2/`
2. Import `petconnect.sql` via phpMyAdmin
3. Visit `http://localhost/a2/`

## Deployment (Coreteaching)

```bash
# Set permissions after uploading
chmod 755 ~/public_html/a2/
chmod 777 ~/public_html/a2/assets/images/pets/
```

- Database on Jacob5 is auto-detected by `db_connect.inc`
- Update Jacob5 password in `includes/db_connect.inc`
