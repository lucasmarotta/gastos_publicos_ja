package obraspublicasja.obraspublicascrawler.vo;

import java.sql.Date;

public class ObraVO 
{
	private Integer id;
	private String nome;
	private Integer estadoId;
	private String local;
	private String orgao;
	private Date dataInicio;
	private Date dataPrevisao;
	private Date dataConclusao;
	private String tipo;
	private String situacao;
	private Double valor;
	private String codOrigem;
	
	public ObraVO() 
	{
		super();
	}
	
	public Integer getId() {
		return id;
	}
	public void setId(Integer id) {
		this.id = id;
	}
	public String getNome() {
		return nome;
	}
	public void setNome(String nome) {
		this.nome = nome;
	}
	public Integer getEstadoId() {
		return estadoId;
	}
	public void setEstadoId(Integer estadoId) {
		this.estadoId = estadoId;
	}
	public String getLocal() {
		return local;
	}
	public void setLocal(String local) {
		this.local = local;
	}
	public String getOrgao() {
		return orgao;
	}
	public void setOrgao(String orgao) {
		this.orgao = orgao;
	}
	public Date getDataInicio() {
		return dataInicio;
	}
	public void setDataInicio(Date dataInicio) {
		this.dataInicio = dataInicio;
	}
	public Date getDataPrevisao() {
		return dataPrevisao;
	}
	public void setDataPrevisao(Date dataPrevisao) {
		this.dataPrevisao = dataPrevisao;
	}
	public Date getDataConclusao() {
		return dataConclusao;
	}
	public void setDataConclusao(Date dataConclusao) {
		this.dataConclusao = dataConclusao;
	}
	public String getTipo() {
		return tipo;
	}
	public void setTipo(String tipo) {
		this.tipo = tipo;
	}
	public String getSituacao() {
		return situacao;
	}
	public void setSituacao(String situacao) {
		this.situacao = situacao;
	}
	public Double getValor() {
		return valor;
	}
	public void setValor(Double valor) {
		this.valor = valor;
	}

	public String getCodOrigem() {
		return codOrigem;
	}

	public void setCodOrigem(String codOrigem) {
		this.codOrigem = codOrigem;
	}

	@Override
	public String toString() {
		return "ObraVO [id=" + id + ", nome=" + nome + ", estadoId=" + estadoId + ", local=" + local + ", orgao="
				+ orgao + ", dataInicio=" + dataInicio + ", dataPrevisao=" + dataPrevisao + ", dataConclusao="
				+ dataConclusao + ", tipo=" + tipo + ", situacao=" + situacao + ", valor=" + valor + ", codOrigem="
				+ codOrigem + "]";
	}
	
	
}
