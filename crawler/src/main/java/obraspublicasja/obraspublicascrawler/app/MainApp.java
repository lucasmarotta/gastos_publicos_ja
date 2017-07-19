package obraspublicasja.obraspublicascrawler.app;

import obraspublicasja.obraspublicascrawler.service.CrawlerService;
import obraspublicasja.obraspublicascrawler.util.DatabaseUtil;
import obraspublicasja.obraspublicascrawler.vo.DatabaseVO;

public class MainApp 
{

	public static void main(String[] args) 
	{
		setDatabase();
		CrawlerService crawlerSrvc = new CrawlerService();
		crawlerSrvc.captureObras();
		closeDatabase();
	}
	
	private static boolean setDatabase()
	{
		DatabaseVO dbvo = new DatabaseVO();
		dbvo.setDriver("com.mysql.cj.jdbc.Driver");
		dbvo.setUrl("jdbc:mysql://obraspublicasbd.codecrafting.net:3306/obraspublicas?useSSL=false&useUnicode=true&useJDBCCompliantTimezoneShift=true&useLegacyDatetimeCode=false&serverTimezone=UTC");
		//dbvo.setUser("root");
		dbvo.setUser("obraspublicas");
		dbvo.setPassword("obras@2016*");
		try
		{
			DatabaseUtil.setDataSource("ObrasPublicas", dbvo);
			return true;
		}catch(Exception e)
		{
			e.printStackTrace();
		}
		return false;
	}
	
	private static void closeDatabase()
	{
		DatabaseUtil.unsetDataSource("ObrasPublicas");
	}

}
