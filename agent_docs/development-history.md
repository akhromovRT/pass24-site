# История разработки

Правило: хранить только последние 10 записей. При добавлении новой переносить старые в
`agent_docs/development-history-archive.md`. Архив читать при необходимости.

---

Краткий журнал итераций проекта.

## Записи

### 2026-02-24 — Фаза 2: домен, SSL, аналитика, Bitrix24

- Домен: pass24pro.ru → 194.87.140.245 (DNS подтверждён)
- SSL: Let's Encrypt сертификат (certbot), автообновление настроено, HSTS включён
- WP URL переключён на https://pass24pro.ru, все ссылки в БД заменены
- GTM-N5S4CR6T подключён через плагин GTM4WP (проверено: код в HTML)
- Яндекс.Метрика 106989472 — прямой код в head через functions.php
- Bitrix24: mu-plugin `pass24-bitrix24.php` — CF7 → crm.lead.add (тест: лид ID 2)
- UTM-параметры сохраняются в куки 30 дней, передаются в CRM
- REST API endpoint: POST /wp-json/pass24/v1/lead (для калькулятора ROI)
- CF7 события → dataLayer (GTM) + Яндекс.Метрика цель demo_request

### 2026-02-23 — Интеграция blueprint и подготовка плана реализации

- Изучён и интегрирован `PASS24_Website_Blueprint_RU.md` (ТЗ на сайт)
- Blueprint перемещён в `agent_docs/blueprint.md` как исходный источник истины по текстам
- Полностью обновлён `agent_docs/architecture.md`: карта сайта, техстек, дизайн-система
- Создан `agent_docs/implementation-plan.md` — детальный чек-лист по 6 фазам (Фазы 1–6)
- Создан `agent_docs/content-strategy.md` — SEO-кластеры, контент-план блога, формулы CTA
- Создан `agent_docs/integrations.md` — AI Sales Factory, скоринг лидов, n8n автопубликация
- Обновлён `agent_docs/adr.md` — 5 архитектурных решений (Bricks Builder, Gravity Forms, Rank Math, n8n, скоринг)
- Обновлён `agent_docs/index.md` — навигация по всем новым документам
- Обновлён `AGENTS.md` — расширенное описание проекта

### 2026-02-23 — Инициализация проекта

- Создан репозиторий на основе шаблона 2030ai/project_template
- Настроено окружение: `.vscode/settings.json`, `.cursorignore`, `.env.example`
