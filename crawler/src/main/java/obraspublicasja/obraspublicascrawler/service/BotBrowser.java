package obraspublicasja.obraspublicascrawler.service;

import java.io.IOException;
import java.net.MalformedURLException;
import java.util.List;
import java.util.logging.Level;

import com.gargoylesoftware.htmlunit.BrowserVersion;
import com.gargoylesoftware.htmlunit.FailingHttpStatusCodeException;
import com.gargoylesoftware.htmlunit.WebClient;
import com.gargoylesoftware.htmlunit.WebRequest;
import com.gargoylesoftware.htmlunit.html.DomNode;
import com.gargoylesoftware.htmlunit.html.HtmlPage;

public class BotBrowser 
{
	private final WebClient client = new WebClient(BrowserVersion.FIREFOX_45);
	private HtmlPage currentPage;
	public BotBrowser()
	{
		setBrowserOptions();
	}
	
	public WebClient getClient()
	{
		return client;
	}
	
	public void refreshPage() throws IOException
	{
		currentPage = (HtmlPage) currentPage.refresh();
	}
	
	public HtmlPage getCurrentPage()
	{
		return currentPage;
	}
	
	public void setCurrentPage(HtmlPage currentPage)
	{
		this.currentPage = currentPage;
	}
	
	public void visitPage(String url) throws FailingHttpStatusCodeException, MalformedURLException, IOException
	{
    	if(!url.isEmpty())
    	{
    		currentPage = client.getPage(url);
    		String encoding = currentPage.getPageEncoding();
    		if(encoding == null || encoding.isEmpty())
    			currentPage = null;
    	}		
	}
	
	public void visitPage(WebRequest request) throws FailingHttpStatusCodeException, IOException
	{
    	if(request != null)
    	{
    		currentPage = client.getPage(request);
    		String encoding = currentPage.getPageEncoding();
    		if(encoding == null || encoding.isEmpty())
    			currentPage = null;
    	}		
	}
	
	public DomNode queryByPath(String query)
	{
		if(currentPage != null)
		{
			DomNode element = currentPage.getFirstByXPath(query);
			if(element != null ) return element;
		}
		return null;
	}
	
	@SuppressWarnings("unchecked")
	public List<DomNode> queryByPathAll(String query)
	{
		if(currentPage != null)
		{
			List<DomNode> element = (List<DomNode>) currentPage.getByXPath(query);
			if(element != null && element.size() > 0) return element;
		}
		return null;
	}
	
	public DomNode querySelector(String query)
	{
		if(currentPage != null)
		{
			DomNode node = currentPage.querySelector(query);
			if(node != null) return node;
		}
		return null;
	}	
	
	public List<DomNode> querySelectorAll(String query)
	{
		if(currentPage != null)
		{
			List<DomNode> node = currentPage.querySelectorAll(query);
			if(node != null && node.size() > 0) return node;
		}
		return null;
	}
	
	private void setBrowserOptions()
	{
		java.util.logging.Logger.getLogger("com.gargoylesoftware").setLevel(Level.OFF);		
		client.getOptions().setTimeout(8000);
    	client.getCookieManager().setCookiesEnabled(true);
    	client.getOptions().setJavaScriptEnabled(true);
    	client.getOptions().setRedirectEnabled(true);
    	client.getOptions().setThrowExceptionOnFailingStatusCode(false);
    	client.getOptions().setThrowExceptionOnScriptError(false);
    	client.getOptions().setActiveXNative(false);
    	client.getOptions().setCssEnabled(false);
    	client.getOptions().setUseInsecureSSL(true);
	}
}
