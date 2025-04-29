CREATE TABLE IF NOT EXISTS contratos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  objeto VARCHAR(255),
  detalhamento VARCHAR(200),  el_item INT,
  valor_anual_estimado DECIMAL(15,2),
  valor_ppag DECIMAL(15,2),
  valor_empenhado DECIMAL(15,2),
  nr_contrato VARCHAR(50),
  meses INT,
  servico_continuado BOOLEAN,
  anos_limite_contratual INT,
  data_inicio DATE,
  data_final DATE,
  empenho BOOLEAN,
  liquidacao BOOLEAN,
  nr_termo_aditivo INT,
  quantidade DECIMAL(10,2),
  distribuicao TEXT,
  processo_sei_sdts VARCHAR(100),
  processo_sei_csm VARCHAR(100),
  status_aditamento VARCHAR(255),
  situacao_aditamento_ano_corrente VARCHAR(255),
  razao_social VARCHAR(255),
  email VARCHAR(255),
  responsavel VARCHAR(255),
  telefone VARCHAR(50),
  situacao VARCHAR(50) DEFAULT 'Não informado',
  observacoes TEXT
);


CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  senha VARCHAR(255)
);

INSERT INTO usuarios (nome, email, senha)
VALUES ('Admin', 'admin@teste.com', MD5('123456'))
ON DUPLICATE KEY UPDATE email = email;
