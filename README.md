# 🚀 Laragon 6 - Backup & Pacotes Atualizados (`packages.conf` + Dashboard `www`)

![Laragon](https://img.shields.io/badge/Laragon-6.0-0E83CD?style=for-the-badge&logo=laragon&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2%20|%208.3%20|%208.4%20|%208.5-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0%20|%208.4%20|%209.1-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Apache](https://img.shields.io/badge/Apache-2.4.x-D22128?style=for-the-badge&logo=apache&logoColor=white)
![Nginx](https://img.shields.io/badge/Nginx-1.28%20|%201.29-009639?style=for-the-badge&logo=nginx&logoColor=white)
![NodeJS](https://img.shields.io/badge/Node.js-22%20|%2023%20|%2025-339933?style=for-the-badge&logo=nodedotjs&logoColor=white)
![Theme](https://img.shields.io/badge/Theme-Light%20%7C%20Dark-informational?style=for-the-badge)
![Responsive](https://img.shields.io/badge/Design-Mobile%20Friendly-success?style=for-the-badge)

Repositório de backup pessoal e comunitário com links e configurações de download do **Laragon 6** revisados manualmente para versões modernas, além de uma **página inicial (`index.php`) aprimorada, responsiva e com suporte a Tema Claro e Escuro** para o diretório `www`.

---

## 📌 Contexto & Motivação

O **[Laragon](https://laragon.org/)** é indiscutivelmente um dos ambientes de desenvolvimento web mais rápidos, leves e práticos para Windows. A versão estável gratuita (Laragon 6) continua funcionando perfeitamente nos dias de hoje.

Contudo, dois pontos costumam demandar atenção em novas instalações:
1. **Links desatualizados:** As URLs padrão do recurso **Quick Add** (`usr/packages.conf`) apontam para versões defasadas de PHP, MySQL, Apache, etc.
2. **Página inicial padrão básica:** O `index.php` original da pasta `www` possui uma listagem simples em texto puro, sem visualização em cards e com baixa usabilidade quando acessado por dispositivos móveis na rede local.

### 💡 A Solução Deste Repositório
- **Central de pacotes atualizada:** Todas as URLs do `packages.conf` foram revisadas, testadas e apontam diretamente para mirrors oficiais e seguros.
- **Dashboard de Projetos (`www/index.php`):** Uma tela inicial modernizada com cards em grid, busca instantânea, Dark Mode e layout responsivo.
- **Restauração rápida pós-formatação:** Basta clonar o repositório e copiar as pastas `usr` e `www` para ter o ambiente 100% pronto.

---

## 🎨 Melhorias da Nova Dashboard (`www/index.php`)

A nova página inicial substitui o visual clássico do Laragon trazendo uma experiência muito mais moderna e produtiva:

- **🌓 Tema Claro & Escuro (Dark Mode):** Alternador no topo com ícones de Sol e Lua; lembra a sua escolha automaticamente via `localStorage` e respeita as preferências nativas do sistema.
- **🔍 Busca Instantânea & Atalho de Teclado:** Campo de filtro em tempo real com ícone de busca; filtre qualquer projeto digitando o nome ou aperte a tecla `/` para focar imediatamente na busca.
- **📁 Contador Integrado com Pasta Amarela Interativa:** Um card elegante e unificado com o ícone clássico de pasta amarela do Windows e contagem ampliada; ao clicar nele, o Windows Explorer é aberto instantaneamente na raiz `www`.
- **🖥️ Cards Visuais com Ícone de Website:** Cada projeto ganha um card interativo com ícone estilizado, suporte a nomes extensos (sem cortes nem reticências), indicador de link e animações suaves ao passar o mouse ou tocar na tela.
- **📊 Badges do Servidor Organizados:** Cabeçalho limpo com chips individuais exibindo o Servidor Web ativo (Apache/Nginx), versão do PHP com atalho para o `phpinfo()`, porta/SSL e diretório `DOCUMENT_ROOT`.
- **📱 Design Totalmente Responsivo:** Ajusta confortavelmente em monitores, tablets e smartphones — perfeito para testes via rede local (Wi-Fi) no celular.
- **🧹 Filtro Inteligente de Diretórios:** Ignora arquivos soltos e pastas ocultas do sistema (iniciadas com ponto `.`), exibindo apenas seus projetos reais em ordem alfabética.


---

## 📦 Pacotes Inclusos no `packages.conf`

| Categoria | Pacotes & Versões Disponíveis | Origem / Observações |
| :--- | :--- | :--- |
| **PHP** | 8.2.27, 8.3.18, 8.4.5, 8.5.0 *(NTS x64)* | [windows.php.net](https://windows.php.net/) |
| **Web Servers** | Apache (2.4.57, 2.4.65, 2.4.66 VS17) <br> Nginx (1.28.0, 1.29.4) | [Apache Lounge](https://www.apachelounge.com/) & [Nginx.org](https://nginx.org/) |
| **Banco de Dados** | MySQL (8.0.40, 8.4.3 LTS, 9.1.0 Innovation) <br> PostgreSQL (15.10, 16.6, 17.2) <br> MongoDB (8.x / 7.x comentados) | Oracle MySQL, EnterpriseDB, MongoDB |
| **Ferramentas DB** | phpMyAdmin (5.2.2, 5.2.3) <br> DBeaver CE (última versão) | phpmyadmin.net / dbeaver.io |
| **Runtimes** | Node.js (22.14 LTS, 23.11, 25.2) <br> Golang (1.23.4, 1.24.1) | nodejs.org / go.dev |
| **Outros / Extras** | PocketBase (0.25.9), VS Code Portable | PocketBase GitHub / Microsoft |

---

## 🛠️ Como Usar (Passo a Passo)

### Cenário 1: Formatou a máquina ou instalou um Laragon novo

1. Baixe e instale o [Laragon 6](https://laragon.org/download/);
2. Clone ou baixe este repositório;
3. Copie/substitua os arquivos nos seguintes caminhos da instalação do seu Laragon:
   - **Configuração de Pacotes:**
     ```text
     Copie: usr\packages.conf  👉  Para: C:\laragon\usr\packages.conf
     ```
   - **Dashboard de Projetos:**
     ```text
     Copie: www\index.php      👉  Para: C:\laragon\www\index.php
     ```
   *(Ou simplesmente copie as pastas `usr` e `www` deste repositório para a raiz `C:\laragon\`)*.

### Cenário 2: Baixando novos pacotes pelo Laragon

1. Abra o **Laragon**;
2. Clique com o botão direito em qualquer lugar da tela do Laragon (ou no ícone da bandeja perto do relógio);
3. Acesse o menu:
   - **Menu** > **Quick App** / **Tools** > **Quick Add**;
4. O Laragon exibirá a lista atualizada com as opções do arquivo `packages.conf` (ex: `*PHP-8.4`, `mysql-8.4`, `node-22.14`, etc.);
5. Clique sobre o pacote desejado: o Laragon fará o download e a extração automática na pasta correspondente (`C:\laragon\bin\...`).

---

## ⚡ Dicas Importantes & Boas Práticas

> [!IMPORTANT]
> **Visual C++ Redistributable (VCRedist):**
> Versões recentes do Apache e do PHP (compiladas em VS16 e VS17) exigem o runtime do Visual Studio C++ instalado no Windows. Se o Apache ou PHP não iniciarem, instale o pacote [Visual C++ Redistributable All-in-One](https://github.com/abbodi1406/vcredist/releases) ou direto do site da Microsoft.

> [!TIP]
> **Troca de Versão em 1 Clique:**
> Após baixar qualquer versão pelo Quick Add:
> - **PHP:** Botão direito no Laragon > *PHP* > *Version* > Selecionar a versão.
> - **MySQL:** Botão direito no Laragon > *MySQL* > *Version* > Selecionar a versão.
> - **Apache:** Botão direito no Laragon > *Apache* > *Version* > Selecionar a versão.

> [!TIP]
> **Testando em Dispositivos Móveis na Rede Local:**
> No Laragon, com o servidor ligado, descubra o IP local do seu computador (`ipconfig` no terminal, ex: `192.168.1.15`). No navegador do seu celular ou tablet conectado na mesma rede Wi-Fi, digite `http://192.168.1.15` para acessar a nova dashboard e abrir qualquer projeto diretamente na tela do aparelho!

---

## 📂 Estrutura do Repositório

```text
.
├── README.md              # Documentação e instruções de uso
├── usr/
│   └── packages.conf      # Configuração com links de download atualizados
└── www/
    └── index.php          # Nova dashboard de projetos (responsiva, em cards)
```

---

## 🤝 Como Atualizar e Manter

Sempre que uma nova versão do PHP, Node.js ou MySQL for lançada:
1. Abra o arquivo `usr/packages.conf`;
2. Atualize o link do pacote seguindo a sintaxe:
   ```conf
   nome-versao=URL_DIRETA_DO_ARQUIVO.zip
   ```
   *(Adicione `*` no início do nome se for um pacote sugerido com destaque, como nos PHPs).*
3. Faça commit e push para manter seu backup na nuvem em dia!
