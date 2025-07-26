# 🏢 Sistema de Quadro Societário

## 📋 Descrição
Sistema completo para cadastro de empresas e seu quadro societário, com:
- Autenticação JWT
- CRUD de Empresas e Sócios
- API RESTful
- Frontend Angular (a implementar)

## 🛠️ Tecnologias
- **Backend**: Symfony 7, PHP 8.3+
- **Banco de Dados**: PostgreSQL
- **Frontend**: Angular 17 (próxima etapa)
- **Autenticação**: JWT

## 🔧 Instalação

### ✅ Pré-requisitos
- PHP 8.3+
- Composer
- PostgreSQL
- Node.js (para o frontend)

### 🚀 Passo a Passo

1. **Clonar o repositório**
   ```bash
   git clone [url-do-repositorio]
   cd quadro-societario-backend
   ```

2. **Configurar ambiente**
   - Copiar `.env.example` para `.env`
   - Definir variáveis `DATABASE_URL` e `JWT_PASSPHRASE`

3. **Instalar dependências**
   ```bash
   composer install
   ```

4. **Configurar Banco de Dados**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Gerar chaves JWT**
   ```bash
   mkdir -p config/jwt
   openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
   openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
   ```

6. **Iniciar servidor**
   ```bash
   symfony serve -d
   ```

## 🌐 Endpoints da API

### 🔐 Autenticação
- `POST /api/login` - Login (retorna JWT)

### 👥 Usuários (ROLE_ADMIN)
- `GET /api/users` - Listar todos
- `POST /api/users` - Criar novo
- `PUT /api/users/{id}` - Atualizar
- `DELETE /api/users/{id}` - Remover

### 🏢 Empresas
- `GET /api/empresas` - Listar
- `POST /api/empresas` - Criar
- `GET /api/empresas/{id}/socios` - Listar sócios

### 👤 Sócios
- `POST /api/empresas/{id}/socios` - Adicionar sócio
- `PUT /api/empresas/{empresaId}/socios/{socioId}` - Atualizar
- `DELETE /api/empresas/{empresaId}/socios/{socioId}` - Remover

## 🏗️ O que foi implementado
- Backend completo em Symfony
- Autenticação JWT
- CRUD de Empresas e Sócios
- Validações customizadas (CPF/CNPJ)
- Arquitetura SOLID
- Frontend Angular (próxima etapa)

## ▶️ Como Executar

### 🔙 Backend
```bash
symfony serve -d
```

Acesse os endpoints via Postman ou Insomnia.

### 🔜 Frontend (quando disponível)
```bash
cd ../quadro-societario-frontend
npm install
ng serve
```

## 📌 Próximos Passos
1. Criar o frontend em Angular 17
2. Implementar login no Angular

---

💡 **Dica**: Teste todos os endpoints no Postman antes de seguir para o frontend!

### 🔍 Exemplo de teste:
```bash
curl -X POST 'http://localhost:8000/api/users' -H 'Content-Type: application/json' -d '{"email": "admin@exemplo.com", "password": "senhaForte123", "roles": ["ROLE_ADMIN"]}'
```