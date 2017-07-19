package obraspublicasja.obraspublicascrawler.dao;

import java.sql.Connection;

import obraspublicasja.obraspublicascrawler.constant.SQLConstants;
import obraspublicasja.obraspublicascrawler.util.DatabaseUtil;

public class EstadoDAO extends BasicDAO
{

	public EstadoDAO(String ds) 
	{
		super(ds);
	}
	
	public Integer getEstadoIdByUF(String uf)
	{
		try
		{
			Connection conn = DatabaseUtil.getDataSourceConnection(ds);
			pstmt = conn.prepareStatement(SQLConstants.GET_ESTADO_ID_BY_UF);
			pstmt.setString(1,uf);
			rs = pstmt.executeQuery();
			if(rs.next()) return rs.getInt(1);			
		}catch(Exception e)
		{
			e.printStackTrace();
		}
		finally
		{
			try
			{
				if(rs != null) rs.close();
				if(pstmt != null) pstmt.close();
				//if(conn != null) conn.close();
			}catch(Exception e)
			{
				e.printStackTrace();
			}			
		}
		return null;
	}

}
