# Projeto AASP - Customização WooCommerce para Cursos Jurídicos

## Funcionalidades

- **Tema Filho (`aasp-storefront-child`):**  
- **Plugin (`aasp-custom-products`):** 

## Requisitos

- ✅ Um ambiente WordPress funcional (local ou em servidor).
- ✅ Plugin WooCommerce instalado e ativo.
- ✅ Tema Storefront instalado.
- ✅ [Git](httpss://git-scm.com/) instalado.
- ✅ [Composer](https://getcomposer.org/) instalado.

---

## Guia de Instalação (a partir do Git)

1.  **Clone o Repositório:**
    Primeiro, clone este repositório para o seu computador.
    ```bash
    git clone git@github.com:daqniwloe/teste-pr-tico.git
    ```

2.  **Posicione os Arquivos:**
    -   Copie a pasta `aasp-storefront-child` do repositório clonado para o diretório `wp-content/themes/` da sua instalação WordPress.
    -   Copie a pasta `aasp-custom-products` do repositório clonado para o diretório `wp-content/plugins/` da sua instalação WordPress.

3.  **Instale as Dependências do Tema:**
    -   Abra seu terminal e navegue até o wp-content:
        ```bash
        cd caminho/para/seu/site/wp-content/themes/aasp-storefront-child
        ```
    -   Execute o Composer para instalar as dependências (Carbon Fields):
        ```bash
        composer install
        ```

4.  **Ative no WordPress:**
    -   No painel do WordPress, vá em **Aparência > Temas** e ative o tema `AASP Storefront Child`.
    -   Em seguida, vá em **Plugins > Plugins Instalados** e ative o plugin `AASP Custom Products Report`.

O projeto está instalado e pronto para ser testado!

---

## Como Testar as Funcionalidades

Após a instalação, siga estes passos para verificar se tudo está funcionando.

1.  **Adicionar Dados aos Produtos:**
    -   Vá em **Produtos > Editar**.
    -   Encontre a seção **"Informações Jurídicas"** e preencha os campos.
    -   Clique em **"Atualizar"**.

2.  **Verificar a Nova Aba:**
    -   Visite a página do produto no frontend do site.
    -   **Verificação:** A aba "Informações Jurídicas" deve aparecer com os dados corretos.

3.  **Acessar o Relatório:**
    -   No menu do painel, clique em **"Produtos Jurídicos"**.
    -   **Verificação:** A tabela de relatório deve ser exibida com os produtos, dados customizados e contagem de pedidos.

4.  **Testar a Validação (Mock):**
    -   Na página de um produto, clique no botão **"Validar conteúdo jurídico..."**.
    -   **Verificação:** Um spinner deve aparecer, seguido de uma mensagem de sucesso ou erro (simulada).
