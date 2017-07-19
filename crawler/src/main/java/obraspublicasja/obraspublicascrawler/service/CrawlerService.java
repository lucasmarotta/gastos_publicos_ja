package obraspublicasja.obraspublicascrawler.service;

import java.sql.Date;
import java.text.SimpleDateFormat;
import java.util.List;

import org.apache.logging.log4j.LogManager;
import org.apache.logging.log4j.Logger;

import com.gargoylesoftware.htmlunit.html.DomNode;
import com.gargoylesoftware.htmlunit.util.UrlUtils;

import obraspublicasja.obraspublicascrawler.constant.AppConstants;
import obraspublicasja.obraspublicascrawler.dao.EstadoDAO;
import obraspublicasja.obraspublicascrawler.dao.ObraDao;
import obraspublicasja.obraspublicascrawler.vo.ObraVO;

public class CrawlerService 
{
	static final Logger logger = LogManager.getLogger(CrawlerService.class.getName());
	private BotBrowser crawler;
	private String currentUF = "ES";
	private Integer currentEstadoID = null;
	private ObraDao obraDAO;
	private EstadoDAO estadoDAO;
	
	public CrawlerService()
	{
		this.crawler = new BotBrowser();
		this.obraDAO = new ObraDao("ObrasPublicas");
		this.estadoDAO = new EstadoDAO("ObrasPublicas");
	}
	
	public void setCurrentUF(String currentUF)
	{
		this.currentUF = currentUF;
	}
	
	public void captureObras()
	{
		currentEstadoID = estadoDAO.getEstadoIdByUF(currentUF);
		if(currentEstadoID != null)
		{
			try 
			{
				crawler.visitPage(AppConstants.BASEURL_ES);
				if(crawler.getCurrentPage() != null)
				{
					System.out.println("HOMEURL UF "+currentUF+" ONLINE");
					crawler.visitPage(AppConstants.HOMEURL_ES);
					if(crawler.getCurrentPage() != null)
					{
						System.out.println("CHECANDO NOVAS OBRAS");
						if(checkQTDObras())
						{
							System.out.println("PAGINAÇÃO UF "+currentUF+" INICIADA");
							paginateTipoObra();
						}
						else System.out.println("NÃO HÁ NOVAS OBRAS");
					}
				}
				else System.out.println("FAIL TO VISIT HOMEURL ES");
				
			} catch (Exception e)
			{
				e.printStackTrace();
			}			
		}
		else System.out.println("Não foi possível obter o ID do Estado "+currentUF);
	}
	
	private boolean checkQTDObras()
	{
		DomNode node = crawler.querySelector(".headerConsulta > tbody > tr:nth-child(1) > td");
		if(node != null)
		{
			int qtdObras = Integer.parseInt(node.asText());
			int qtdObrasDB = obraDAO.getQTDObraByUF(currentUF);
			return (qtdObras == qtdObrasDB) ? false:true;
		}
		return false;
	}
	
	private void paginateTipoObra()
	{
		DomNode node = crawler.querySelector("#nPages");
		int qtdPgTipoObra = Integer.parseInt(node.asText().replace("Página 1 | ","").trim());
		int pgTipoObra = 1;
		do
		{
			if(pgTipoObra > 1)
			{
				try
				{
					crawler.visitPage(AppConstants.HOMEURL_ES+"&Pagina="+pgTipoObra);
					System.out.println("TIPO OBRA PÁGINA "+pgTipoObra);
				}
				catch(Exception e)
				{
					System.out.println("FALHA AO VISITAR PÁGINA DE TIPO OBRA N: "+pgTipoObra);
					crawler.setCurrentPage(null);
					e.printStackTrace();
				}				
			}
			else System.out.println("TIPO OBRA PÁGINA "+pgTipoObra);
			if(crawler.getCurrentPage() != null)
			{
				List<DomNode> nodeList = crawler.queryByPathAll("//*[@id='tbDados']/tbody/tr/td[1]/a/@href");
				for (DomNode domNode : nodeList) 
				{
					String tipoObraUrl = domNode.getNodeValue();
					try
					{
						crawler.visitPage(AppConstants.BASEURL_ES+UrlUtils.encodeAnchor(tipoObraUrl));
						if(crawler.getCurrentPage() != null)
						{
							System.out.println("PAGINANDO TIPO OBRA: "+tipoObraUrl);
							paginateObra(tipoObraUrl);
						}
					}catch(Exception e)
					{
						e.printStackTrace();
					}
				}				
			}
			pgTipoObra++;
		}while(pgTipoObra <= qtdPgTipoObra);
	}
	
	private void paginateObra(String listaObraUrl)
	{
		DomNode node = crawler.querySelector("#nPages");
		int qtdPgObra = Integer.parseInt(node.asText().replace("Página 1 | ","").trim());
		int pgObra = 1;
		do
		{
			if(pgObra > 1)
			{
				try
				{
					crawler.visitPage(listaObraUrl+"&Pagina="+pgObra);
					System.out.println("OBRA PÁGINA "+pgObra);
				}
				catch(Exception e)
				{
					System.out.println("FALHA AO VISITAR PÁGINA DE OBRA N: "+pgObra);
					crawler.setCurrentPage(null);
					e.printStackTrace();
				}				
			}
			else System.out.println("OBRA PÁGINA 1");
			if(crawler.getCurrentPage() != null)
			{
				List<DomNode> nodeList = crawler.queryByPathAll("//*[@id='tbDados']/tbody/tr/td[1]/a/@href");
				for (DomNode domNode : nodeList) 
				{
					String obraUrl = domNode.getNodeValue();
					String codOrigem = getUrlID(obraUrl);
					if(!obraDAO.checkObraByCodOrigem(codOrigem))
					{
						try
						{
							crawler.visitPage(AppConstants.BASEURL_ES+UrlUtils.encodeAnchor(obraUrl));
							if(crawler.getCurrentPage() != null)
							{
								if(saveObra(codOrigem)) System.out.println("OBRA "+codOrigem+" CAPTURADA");
								else System.out.println("FALHA AO CAPTURAR OBRA "+codOrigem);
							}
						}catch(Exception e)
						{
							e.printStackTrace();
						}					
					}
					else System.out.println(codOrigem+" JÁ CAPTURADO");
				}				
			}
			pgObra++;
		}while(pgObra <= qtdPgObra);
	}
	
	private boolean saveObra(String codOrigem)
	{
		List<DomNode> nodeList = crawler.querySelectorAll("#tbDados tbody tr");
		ObraVO obraVO = new ObraVO();
		obraVO.setEstadoId(currentEstadoID);
		obraVO.setCodOrigem(codOrigem);
		for (DomNode domNode : nodeList) 
		{
			String nodeText = domNode.asText();
			String attr = nodeText.substring(0, nodeText.indexOf(":")+1);
			if(attr.toLowerCase().contains("obra")) 
				obraVO.setNome(nodeText.replaceFirst(attr,"").replaceAll("\\s+"," ").trim());
			else if(attr.toLowerCase().contains("localidade"))
				obraVO.setLocal(nodeText.replaceFirst(attr, "").replaceAll("\\s+", " ").trim());
			else if(attr.toLowerCase().contains("órgão"))
				obraVO.setOrgao(nodeText.replaceFirst(attr, "").replaceAll("\\s+", " ").trim());
			else if(attr.toLowerCase().contains("tipo"))
				obraVO.setTipo(nodeText.replaceFirst(attr, "").replaceAll("\\s+", " ").trim());
			else if(attr.toLowerCase().contains("valor total"))
				obraVO.setValor(Double.parseDouble(nodeText.replaceFirst(attr, "").replaceAll("\\s+", " ").replace("R$","").replace(".","").replace(",",".").trim()));
			else if(attr.toLowerCase().contains("início"))
				obraVO.setDataInicio(stringToSQLDate(nodeText.replaceFirst(attr, "").replaceAll("\\s+", " ").trim()));			
			else if(attr.toLowerCase().contains("conclusão prevista"))
				obraVO.setDataPrevisao(stringToSQLDate(nodeText.replaceFirst(attr, "").replaceAll("\\s+", " ").trim()));
			else if(attr.toLowerCase().contains("conclusão realizada"))
				obraVO.setDataConclusao(stringToSQLDate(nodeText.replaceFirst(attr, "").replaceAll("\\s+", " ").trim()));
			else if(attr.toLowerCase().contains("situacão"))
			{
				String aux = nodeText.replaceFirst(attr, "").replaceAll("\\s+", " ").trim().toLowerCase();
				if(aux.contains("concl")) obraVO.setSituacao("concluída");
				else if(aux.contains("contrat")) obraVO.setSituacao("em contratação");
				else if(aux.contains("em obra") || aux.contains("análise")) obraVO.setSituacao("em andamento");
				else obraVO.setSituacao("atrasada");
			}
		}
		return obraDAO.saveObra(obraVO);
	}
	
	private String getUrlID(String url)
	{
		String urlParams = url.substring((url.indexOf("?")+1),(url.length()));
		String[] urlParamsArr = urlParams.split("&");
		return urlParamsArr[urlParamsArr.length-1].replace("id=","").trim();
	}
	
	private Date stringToSQLDate(String dt)
	{
		if(dt.length() > 0)
		{
			String outputFormat = "yyyy-MM-dd";
			String inputFormat = "dd/MM/yyyy";
			
			if(dt.length() == 7) dt = "01/"+dt;
			SimpleDateFormat dateParser = new SimpleDateFormat(inputFormat);
			try
			{
				java.util.Date date = dateParser.parse(dt);
				SimpleDateFormat dateFormatter = new SimpleDateFormat(outputFormat);
				return Date.valueOf(dateFormatter.format(date));	
			}catch(Exception e)
			{
				e.printStackTrace();
			}			
		}	
		return null;
	}
}
