package obraspublicasja.obraspublicascrawler.constant;

public class SQLConstants 
{
	//OBRA
	public static final String GET_QTD_OBRA_BY_UF = "SELECT count(Obra.id) qtd FROM Obra INNER JOIN Estado ON estadoId = Estado.id WHERE sigla = ?";
	public static final String GET_OBRA_BY_COD_ORIGEM = "SELECT * FROM Obra WHERE codOrigem = ?";
	public static final String INSERT_OBRA = "INSERT INTO Obra(nome,estadoId,local,orgao,dataInicio,dataPrevisao,dataConclusao,tipo,situacao,valor,codOrigem) "
											+ "VALUES (?,?,?,?,?,?,?,?,?,?,?)";
	//ESTADO
	public static final String GET_ESTADO_ID_BY_UF = "SELECT id FROM Estado WHERE sigla = ?";
}
