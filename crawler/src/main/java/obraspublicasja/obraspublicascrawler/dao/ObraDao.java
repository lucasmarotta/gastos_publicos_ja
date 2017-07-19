package obraspublicasja.obraspublicascrawler.dao;

import java.sql.Connection;
import java.sql.Statement;

import obraspublicasja.obraspublicascrawler.constant.SQLConstants;
import obraspublicasja.obraspublicascrawler.util.DatabaseUtil;
import obraspublicasja.obraspublicascrawler.vo.ObraVO;

public class ObraDao extends BasicDAO
{
	public ObraDao(String ds) 
	{
		super(ds);
	}
	
	public Integer getQTDObraByUF(String uf)
	{
		try
		{
			Connection conn = DatabaseUtil.getDataSourceConnection(ds);
			pstmt = conn.prepareStatement(SQLConstants.GET_QTD_OBRA_BY_UF);
			pstmt.setString(1,uf);
			rs = pstmt.executeQuery();
			if(rs.next()) return rs.getInt(1);
		}
		catch(Exception e)
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
	
	public boolean checkObraByCodOrigem(String codOrigem)
	{
		try
		{
			Connection conn = DatabaseUtil.getDataSourceConnection(ds);
			pstmt = conn.prepareStatement(SQLConstants.GET_OBRA_BY_COD_ORIGEM);
			pstmt.setString(1, codOrigem);
			rs = pstmt.executeQuery();
			return rs.next();
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
		return false;
	}
	
	public boolean saveObra(ObraVO obraVO)
	{
		try
		{
			Connection conn = DatabaseUtil.getDataSourceConnection(ds);
			pstmt = conn.prepareStatement(SQLConstants.INSERT_OBRA,Statement.RETURN_GENERATED_KEYS);
			pstmt.setString(1, obraVO.getNome());
			pstmt.setInt(2, obraVO.getEstadoId());
			pstmt.setString(3, obraVO.getLocal());
			pstmt.setString(4, obraVO.getOrgao());
			pstmt.setDate(5, obraVO.getDataInicio());
			pstmt.setDate(6, obraVO.getDataPrevisao());
			pstmt.setDate(7,obraVO.getDataConclusao());
			pstmt.setString(8, obraVO.getTipo());
			pstmt.setString(9, obraVO.getSituacao());
			pstmt.setDouble(10, obraVO.getValor());
			pstmt.setString(11, obraVO.getCodOrigem());
			pstmt.execute();
			rs = pstmt.getGeneratedKeys();
			return rs.next();
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
		return false;
	}
}
