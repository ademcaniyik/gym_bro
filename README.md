# GymBro

## Proje Amacı
Kullanıcıların Google ile giriş yapabildiği, antrenman planlarını günlere ve hareketlere bölerek oluşturabildiği, her hareket için birden fazla set/tekrar/kilo girişi yapabildiği, geçmiş antrenmanlarını görebildiği ve gelişimini takip edebildiği modern bir antrenman takip sistemi.

## Kurulum
1. **Klonla:**
   ```bash
   git clone <repo-url>
   cd gym_bro
   ```
2. **Composer ile bağımlılıkları yükle:**
   ```bash
   composer install
   ```
3. **Veritabanı ayarlarını .env dosyasına gir:**
   ```env
   DB_HOST=localhost
   DB_NAME=gym_bro
   DB_USER=root
   DB_PASS=your_password
   GOOGLE_CLIENT_ID=xxx
   GOOGLE_CLIENT_SECRET=xxx
   JWT_SECRET=xxx
   ```
4. **Veritabanı tablolarını oluştur:**
   (SQL dosyası veya migration komutları eklenmeli)
5. **Sunucuyu başlat:**
   - XAMPP/WAMP ile veya `php -S localhost:8000 -t public` komutu ile

## Kullanım
- `public/` dizinine tarayıcıdan girerek Google ile giriş yapabilirsin.
- Dashboard üzerinden antrenman planı oluşturabilir, geçmişini ve gelişimini görebilirsin.

## API Kullanımı
Tüm endpointler JWT ile korunur. Örnek:
```http
POST /public/api/login.php
{
  "id_token": "GOOGLE_ID_TOKEN"
}
```
Yanıt:
```json
{
  "token": "...jwt..."
}
```

### Diğer endpointler ve örnekler için: `public/api/api_kullanim_klavuzu.txt`

## Ekran Görüntüleri
- ![Landing Page](docs/landing.png)
- ![Dashboard](docs/dashboard.png)
- ![Antrenman Girişi](docs/workout_entry.png)

## Katkı ve Geliştirme
- Kodun tamamı merkezi CSS ile responsive ve modülerdir.
- Yeni özellikler ve pull request'ler için roadmap ve issues takip edilmeli.
