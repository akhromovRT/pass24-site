# История разработки

Правило: хранить только последние 10 записей. При добавлении новой переносить старые в
`agent_docs/development-history-archive.md`. Архив читать при необходимости.

---

Краткий журнал итераций проекта.

## Записи

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
