-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 26, 2023 at 03:52 AM
-- Server version: 10.5.17-MariaDB-cll-lve
-- PHP Version: 8.2.3

SET
SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET
time_zone = "+00:00";

--
-- Database: `flisoldf_api`
--

-- --------------------------------------------------------

--
-- Dumping data for table `collaboration_area`
--

INSERT INTO `collaboration_area` (`id`, `name`)
VALUES (1, 'Na busca por patrocínio e apoio'),
       (2, 'Na publicidade e divulgação (redes sociais)'),
       (3, 'Na organização do blog'),
       (4, 'No controle de inscrição (participantes e palestrantes), certificados e controle das atividades'),
       (5, 'Na infra-estrutura tecnológica (rede local e wifi, servidores e repositórios)'),
       (6, 'Na oficina de instalação (InstallFest) e gravação de imagens em mídias'),
       (7, 'Temário (Outras palestras, Oficinas e Mini-cursos)'),
       (8, 'Sistema de inscrição'),
       (9, 'Organização de salas'),
       (10, 'Visita e divulgação em outras IES e escolas'),
       (11, 'Filmagem, fotografia e documentário do evento'),
       (12, 'Arte Gráfica e material gráfico'),
       (13, 'Comunicação colaborativa (divulgação em tempo real nas redes sociais)');

-- --------------------------------------------------------

--
-- Dumping data for table `collaborator_availability`
--

INSERT INTO `collaborator_availability` (`id`, `name`)
VALUES (1, 'Manhã - Todos os dias'),
       (2, 'Tarde - Todos os dias'),
       (3, 'Noite - Todos os dias'),
       (4, 'Sábado pela manhã'),
       (5, 'Sábado pela tarde');

-- --------------------------------------------------------

--
-- Dumping data for table `distro`
--

INSERT INTO `distro` (`id`, `name`)
VALUES (1, 'Não utilizo Linux'),
       (2, 'BigLinux'),
       (3, 'Debian'),
       (4, 'Duzeru'),
       (5, 'Educatux'),
       (6, 'Elementary OS'),
       (7, 'Fedora'),
       (8, 'Kaiana'),
       (9, 'Kali Linux'),
       (10, 'LinuxMint'),
       (11, 'LXLE'),
       (12, 'SlackWare'),
       (13, 'Suse'),
       (14, 'Tails'),
       (15, 'Trisquel'),
       (16, 'Ubuntu/Kubuntu'),
       (17, 'Outra');

-- --------------------------------------------------------

--
-- Dumping data for table `edition`
--

INSERT INTO `edition` (`id`, `year`, `active`, `created_at`, `updated_at`, `removed_at`)
VALUES (1, '2005', 0, '2019-03-03 23:19:52', '2019-03-03 23:19:52', NULL),
       (2, '2006', 0, '2019-03-03 23:19:52', '2019-03-03 23:19:52', NULL),
       (3, '2007', 0, '2019-03-03 23:19:52', '2019-03-03 23:19:52', NULL),
       (4, '2008', 0, '2019-03-03 23:19:52', '2019-03-03 23:19:52', NULL),
       (5, '2009', 0, '2019-03-03 23:19:52', '2019-03-03 23:19:52', NULL),
       (6, '2010', 0, '2019-03-03 23:19:52', '2019-03-03 23:19:52', NULL),
       (7, '2011', 0, '2019-03-03 23:19:52', '2019-03-03 23:19:52', NULL),
       (8, '2012', 0, '2019-03-03 23:19:52', '2019-03-03 23:19:52', NULL),
       (9, '2013', 0, '2019-03-03 23:19:52', '2019-03-03 23:19:52', NULL),
       (10, '2014', 0, '2019-03-03 23:19:52', '2019-03-03 23:19:52', NULL),
       (11, '2015', 0, '2019-03-03 23:19:52', '2019-03-03 23:19:52', NULL),
       (12, '2016', 0, '2019-03-03 23:19:52', '2019-03-03 23:19:52', NULL),
       (13, '2017', 0, '2019-03-03 23:19:52', '2019-03-03 23:19:52', NULL),
       (14, '2018', 0, '2019-03-03 23:19:52', '2019-03-03 23:19:52', NULL),
       (15, '2019', 0, '2019-03-03 23:19:52', '2019-03-03 23:19:52', NULL),
       (16, '2020', 0, '2020-03-08 20:47:00', '2020-03-08 20:47:00', NULL),
       (17, '2021', 0, '2021-03-28 21:31:15', '2021-03-28 21:31:15', NULL),
       (18, '2022', 0, '2022-03-28 21:31:15', '2022-03-28 21:31:15', NULL),
       (19, '2023', 1, '2023-02-19 17:53:31', '2022-03-28 21:31:15', NULL);

-- --------------------------------------------------------

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `acronym`, `created_at`, `updated_at`, `removed_at`)
VALUES (1, 'Acre', 'AC', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (2, 'Alagoas', 'AL', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (3, 'Amapá', 'AP', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (4, 'Amazonas', 'AM', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (5, 'Bahia', 'BA', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (6, 'Ceará', 'CE', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (7, 'Distrito Federal', 'DF', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (8, 'Espírito Santo', 'ES', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (9, 'Goiás', 'GO', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (10, 'Maranhão', 'MA', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (11, 'Mato Grosso', 'MT', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (12, 'Mato Grosso do Sul', 'MS', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (13, 'Minas Gerais', 'MG', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (14, 'Pará', 'PA', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (15, 'Paraíba', 'PB', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (16, 'Paraná', 'PR', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (17, 'Pernambuco', 'PE', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (18, 'Piauí', 'PI', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (19, 'Rio de Janeiro', 'RJ', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (20, 'Rio Grande do Norte', 'RN', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (21, 'Rio Grande do Sul', 'RS', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (22, 'Rondônia', 'RO', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (23, 'Roraima', 'RR', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (24, 'Santa Catarina', 'SC', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (25, 'São Paulo', 'SP', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (26, 'Sergipe', 'SE', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL),
       (27, 'Tocantins', 'TO', '2022-04-05 22:49:38', '2022-04-05 22:49:38', NULL);

-- --------------------------------------------------------

--
-- Dumping data for table `student_info`
--

INSERT INTO `student_info` (`id`, `name`)
VALUES (1, 'Sim, no ensino médio'),
       (2, 'Sim, no ensino médio técnico'),
       (3, 'Sim, no ensino superior'),
       (4, 'Não, já terminei o ensino superior'),
       (5, 'Não, estou apenas trabalhando'),
       (6, 'Não estou estudando, nem trabalhando'),
       (7, 'Não, sou professor');

-- --------------------------------------------------------

--
-- Dumping data for table `talk_subject`
--

INSERT INTO `talk_subject` (`id`, `name`)
VALUES (1, 'Acessibilidade Livre (Aplicativos para portadores de necessidades física)'),
       (2, 'Criptotecnologias'),
       (3, 'Desenvolvimento (Programação)'),
       (4, 'Design de Imagens'),
       (5, 'Ecossistema de Software Livre'),
       (6, 'Educação'),
       (7, 'Flisolzinho (Oficina voltada para crianças)'),
       (8, 'Games'),
       (9, 'Gestão de Projetos'),
       (10, 'Governança de Dados'),
       (11, 'Internet das Coisas'),
       (12, 'Redes'),
       (13, 'Robótica Livre'),
       (14, 'Segurança e Privacidade'),
       (15, 'Sistemas Operacionais'),
       (16, 'Software Público'),
       (17, 'Startups e Empreendedorismo'),
       (18, 'TI Verde (Sustentabilidade)'),
       (19, 'Web');

-- --------------------------------------------------------
