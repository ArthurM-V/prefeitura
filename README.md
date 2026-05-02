# 🏛️ Portal do Cidadão — Prefeitura Municipal

Sistema de gerenciamento de chamados públicos desenvolvido como trabalho final da disciplina de Linguagens de Programação.

## Stack utilizada

- **Frontend:** HTML5, CSS3, Alpine.js v3
- **Backend:** PHP 8.1+ (arquitetura MVC)
- **Banco de dados:** MySQL 8+
- **Servidor:** Apache com mod_rewrite

---

## Estrutura do projeto

```
prefeitura/
├── .htaccess                   ← Redireciona tudo para /public
├── app/
│   ├── controllers/
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   └── PortalController.php
│   ├── models/
│   │   ├── Categoria.php
│   │   ├── Chamado.php
│   │   ├── Feedback.php
│   │   ├── Historico.php
│   │   ├── Orgao.php
│   │   ├── Status.php
│   │   └── Usuario.php
│   └── views/
│       ├── admin/
│       │   ├── chamados.php
│       │   ├── dashboard.php
│       │   ├── navbar.php
│       │   └── ver_chamado.php
│       ├── portal/
│       │   └── index.php
│       └── shared/
│           ├── footer.php
│           ├── header.php
│           └── login.php
├── config/
│   ├── app.php
│   └── database.php
├── database/
│   └── schema.sql
└── public/                     ← Document root do servidor
    ├── .htaccess               ← Front controller
    ├── index.php               ← Roteador principal
    ├── css/
    │   └── style.css
    ├── js/
    │   └── app.js
    └── imgs/
```

---

## Instalação

### 1. Banco de dados

Importe o arquivo SQL no MySQL:

```bash
mysql -u root -p < database/schema.sql
```

Ou via phpMyAdmin: importe o arquivo `database/schema.sql`.

### 2. Configuração

Edite `config/database.php` com suas credenciais:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'prefeitura_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

Edite `config/app.php` se necessário:

```php
define('BASE_URL', '/prefeitura/public');
```

### 3. Servidor Apache

- Certifique-se que o `mod_rewrite` está habilitado
- Coloque a pasta `prefeitura/` dentro do `htdocs` (XAMPP) ou `www` (WAMP)
- O document root deve apontar para a pasta `public/`

### 4. Acesso

| URL                                                  | Descrição      |
| ---------------------------------------------------- | -------------- |
| `http://localhost/prefeitura/public/`                | Portal público |
| `http://localhost/prefeitura/public/login`           | Login do admin |
| `http://localhost/prefeitura/public/admin/dashboard` | Dashboard      |

---

## Credenciais padrão

| Campo  | Valor                     |
| ------ | ------------------------- |
| E-mail | `admin@prefeitura.gov.br` |
| Senha  | `password`                |

> ⚠️ Altere a senha em produção. O hash no `schema.sql` corresponde à string `password` gerada pelo `password_hash()` do PHP.

---

## Diagrama de classes (resumo)

| Classe      | Responsabilidade                                    |
| ----------- | --------------------------------------------------- |
| `Usuario`   | Autenticação e cadastro de cidadãos e admins        |
| `Chamado`   | CRUD de chamados, filtros, estatísticas             |
| `Categoria` | Categorias dos chamados (iluminação, pavimentação…) |
| `Orgao`     | Órgãos responsáveis pela execução                   |
| `Status`    | Controle do fluxo de status dos chamados            |
| `Feedback`  | Respostas do admin ao cidadão                       |
| `Historico` | Registro de todas as ações realizadas               |

---

## Fluxo principal

```
Cidadão abre chamado (portal)
    → Admin visualiza no dashboard
    → Admin atribui órgão responsável
    → Admin altera status (Em Análise → Em Andamento)
    → Órgão resolve
    → Admin envia feedback ao cidadão
    → Chamado marcado como "Resolvido"
    → Aparece na listagem pública do portal
```

---

## Rotas disponíveis

### Públicas

| Método   | Rota            | Ação                  |
| -------- | --------------- | --------------------- |
| GET      | `/`             | Portal público        |
| POST     | `/novo-chamado` | Enviar chamado (JSON) |
| GET/POST | `/login`        | Autenticação          |
| GET      | `/logout`       | Encerrar sessão       |

### Admin (requer autenticação)

| Método | Rota                     | Ação                          |
| ------ | ------------------------ | ----------------------------- |
| GET    | `/admin/dashboard`       | Dashboard com métricas        |
| GET    | `/admin/chamados`        | Listar chamados (com filtros) |
| GET    | `/admin/chamado/{id}`    | Detalhe do chamado            |
| POST   | `/admin/atribuir-orgao`  | Atribuir órgão                |
| POST   | `/admin/alterar-status`  | Alterar status                |
| POST   | `/admin/enviar-feedback` | Feedback ao cidadão           |
| POST   | `/admin/excluir-chamado` | Excluir chamado               |
