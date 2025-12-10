# Hpterraplenagem

# Sistema de Controle de Máquinas (Horímetro)

Sistema web desenvolvido em PHP para controle de horas trabalhadas de máquinas e operadores. O projeto realiza o cálculo automático de horas (Final - Inicial) e permite a exportação de relatórios mensais para Excel (CSV).

## Funcionalidades

- **Login de Usuários:** Sistema de autenticação.
- **Controle de Acesso:**
  - **Operador:** Apenas lança os registros (Máquina, Serviço, Horas).
  - **Admin:** Tem acesso exclusivo ao botão de exportar relatórios.
- **Cálculo Automático:** Subtrai o horímetro final do inicial para gerar o total.
- **Registro de Serviços:** Campo para detalhar o serviço realizado ou empresa contratante.
- **Relatórios:** Exportação de dados filtrados pelo mês atual em formato `.csv`.
- **Interface:** Estilização limpa com CSS personalizado.

## Tecnologias Utilizadas

- **Back-end:** PHP (Nativo)
- **Banco de Dados:** MySQL
- **Servidor Local:** Apache (XAMPP)
- **Front-end:** HTML5 & CSS3
