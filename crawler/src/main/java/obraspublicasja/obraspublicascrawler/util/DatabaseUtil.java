package obraspublicasja.obraspublicascrawler.util;

import java.beans.PropertyVetoException;
import java.sql.Connection;
import java.sql.SQLException;
import java.util.HashMap;
import java.util.logging.Level;

import com.mchange.v2.c3p0.ComboPooledDataSource;

import obraspublicasja.obraspublicascrawler.vo.DatabaseVO;

public class DatabaseUtil 
{
	private static HashMap<String,ComboPooledDataSource> cpds = new HashMap<String,ComboPooledDataSource>();
	
	public static void setDataSource(String dsName, DatabaseVO dbvo) throws PropertyVetoException
	{
		java.util.logging.Logger.getLogger("com.mchange.v2.log").setLevel(Level.OFF);
		java.util.logging.Logger.getLogger("com.mchange.v2.c3p0").setLevel(Level.OFF);
		ComboPooledDataSource newCtds = new ComboPooledDataSource();
		newCtds.setDriverClass(dbvo.getDriver());
		newCtds.setJdbcUrl(dbvo.getUrl());
		newCtds.setUser(dbvo.getUser());
		newCtds.setPassword(dbvo.getPassword());
		newCtds.setCheckoutTimeout(3000);
		cpds.put(dsName, newCtds);
	}
	
	public static ComboPooledDataSource getDataSource(String dsName)
	{
		return cpds.get(dsName);
	}
	
	public static Connection getDataSourceConnection(String dsName) throws SQLException
	{
		if(cpds.containsKey(dsName))
		{
			return cpds.get(dsName).getConnection();
		}
		return null;
	}
	
	public static boolean unsetDataSource(String dsName)
	{
		if(cpds.containsKey(dsName))
		{
			cpds.get(dsName).close();
			cpds.remove(dsName);
			return true;
		}
		return false;
	}
}
