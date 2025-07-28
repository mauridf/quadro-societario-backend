# 🏢 Sistema de Quadro Societário - Backend

## 📋 Descrição
Sistema completo para cadastro de empresas e seu quadro societário, com:
- Autenticação JWT
- CRUD de Empresas e Sócios
- API RESTful
- Frontend Angular (a implementar)

## 🛠️ Tecnologias
- **Backend**: Symfony 7, PHP 8.3+
- **Banco de Dados**: PostgreSQL
- **Frontend**: [Angular 17](https://github.com/mauridf/quadro-societario-frontend)
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
   ou 
   
   ```bash
   php -S localhost:8000 -t public
   ```

## 🌐 Endpoints da API

### 🔐 Autenticação
- `POST /api/login` - Login (retorna JWT)
- `GET /api/auth/me` - Informações do Usuário Logado

### 👥 Usuários (ROLE_ADMIN)
- `GET /api/users` - Listar todos
- `GET /api/users/{id}` - Buscar Usuário por Id
- `POST /api/users` - Criar novo
- `PUT /api/users/{id}` - Atualizar
- `DELETE /api/users/{id}` - Remover

### 🏢 Empresas
- `GET /api/empresas` - Listar
- `POST /api/empresas` - Criar
- `GET /api/empresas/{id}` - Buscar Empresa por Id
- `PUT /api/empresas/{id}` - Atualizar
- `DELETE /api/empresas/{id}` - Remover

### 👤 Sócios
- `POST /api/empresas/{id}/socios` - Adicionar sócio
- `GET /api/empresas/{empresaId}/socios` - Listar Sócios da Empresa
- `GET /api/empresas/{empresaId}/socios/{socioId}` - Buscar Sócio por Id da Empresa
- `PUT /api/empresas/{empresaId}/socios/{socioId}` - Atualizar
- `DELETE /api/empresas/{empresaId}/socios/{socioId}` - Remover

## 🏗️ O que foi implementado
- Backend completo em Symfony
- Autenticação JWT
- CRUD de Empresas e Sócios
- Validações customizadas (CPF/CNPJ)
- Arquitetura SOLID
- Frontend Angular [Angular 17](https://github.com/mauridf/quadro-societario-frontend)

## ▶️ Como Executar

### 🔙 Backend
```bash
symfony serve -d
```
ou 
```bash
php -S localhost:8000 -t public
```

Acesse os endpoints via Postman ou Insomnia.

### 🔜 Frontend
```bash
cd ../quadro-societario-frontend
npm install
ng serve
```

### 🔍 Exemplo de teste:
```bash
curl -X POST 'http://localhost:8000/api/users' -H 'Content-Type: application/json' -d '{"email": "admin@exemplo.com", "password": "senhaForte123", "roles": ["ROLE_ADMIN"]}'
```
