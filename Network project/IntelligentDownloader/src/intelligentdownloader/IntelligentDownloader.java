/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package intelligentdownloader;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.net.URL;
import java.net.URLConnection;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.Calendar;
import java.util.Date;
import java.util.GregorianCalendar;
import java.util.PriorityQueue;


/**
 *
 * @author Vikram
 */

class ver implements Comparable{
    int key=0;
    public ver(int a){
        key=a;
    }

    @Override
    public int compareTo(Object o) {
        ver b=(ver)o;
         if(key>b.key)
             return 1;
         else
             return 0;
        //throw new UnsupportedOperationException("Not supported yet."); //To change body of generated methods, choose Tools | Templates.
    }
}



class DownloadRecord implements Comparable{
    
    int priority;
    String url;
    
    
    @Override
    public int compareTo(Object o) {
        DownloadRecord temp=(DownloadRecord)o;
        if(priority>temp.priority)
            return 1;
        else if(priority < temp.priority)
            return -1;
        else
            return 0;
        //throw new UnsupportedOperationException("Not supported yet."); //To change body of generated methods, choose Tools | Templates.
    }
    
}


public class IntelligentDownloader {

    /**
     * @param args the command line arguments
     */
    
    static   PriorityQueue<DownloadRecord> recordTree = new PriorityQueue<>();
    static   boolean RunServer;
    
    public static void main(String[] args) throws IOException,SQLException, InstantiationException, ClassNotFoundException, IllegalAccessException, Exception {
        // TODO code application logic here
        /****************************************************************************************************
        float a;
        a=testInternet();
        System.out.print(a);
        
        
         Class.forName("com.mysql.jdbc.Driver").newInstance();
         Connection con = DriverManager.getConnection("jdbc:mysql://localhost/downloads","root","");
         Statement stmt=con.createStatement();
         String sm="select * from example ";
         ResultSet rs=stmt.executeQuery(sm);
         while(rs.next()){
             System.out.println("The value for key "+rs.getInt("key")+"is    "+rs.getInt("val"));
         }
         PriorityQueue<ver> li=new PriorityQueue<ver>();
        li.add(new ver(1));
        li.add(new ver(2));
        li.add(new ver(3));
        li.add(new ver(0));
        li.add(new ver(29));
        for(ver k:li){
            k.key=k.key+100;
            //System.out.println(k);
        }
        for(ver k:li){
            //k=k+1;
            System.out.println(k.key);
        }
         
         *************************************************************************************************/
      
        //declaration of variables.... start 
        ResultSet rs;
        boolean initially;
        int i,j,hour;
        float k,l;
        final float factor;
        Connection con;
        String  read,write;
        float[] history,estimatedFuture;
        Statement readStmt,writeStmt;
        
        //end of declaration of variables
        
        // boot strap
        initially = true;
        factor=(float) 0.25;
        con=DriverManager.getConnection("jdbc:mysql://localhost/network","root","");
        hour =0;
        RunServer = true;
        history=new float[24];
        estimatedFuture = new float[24];
        for(i=0;i<24;i++){
            history[i]=-1;
            estimatedFuture[i]=0;
        }
        Class.forName("com.mysql.jdbc.Driver").newInstance();
        readStmt=con.createStatement();
        writeStmt = con.createStatement();
        //Process p1 =Runtime.getRuntime().exec("cmd.exe");
//        System.l
        //boot strap ends
        
        while(RunServer){
            try{
                
                //System.out.println("hi");
                k = (float) 0.0;
                //Indicate that java program is processing
                write = "update status set value = '0' where kindof = '0' ";
                writeStmt.execute(write);
                //first do the record information maintanence.
                deleteDownloads(readStmt,writeStmt);//to delete the already scheduled entries from the database.
                updateDownloadPriority(readStmt,writeStmt);//to upgrade the priority of the existing records.
                addNewDownloads(readStmt,writeStmt);//to add the newly added entries into the priority queue from the database.
                
                //now do network analysis and determine the ones to be downloaded.
                
                for(j=0;j<3;j++){
                    //k = k + ((testInternet())/3);
                }
                history[hour]=k;
                if(initially){
                    estimatedFuture[(hour+1)%24]= (float)factor*(estimatedFuture[hour]) + (float)( 1 - factor)*(history[hour]);
                }
                else{
                    l= estimatedFuture[(hour+1)%24];
                    estimatedFuture[(hour+1)%24]  = (float)(((float)factor*(estimatedFuture[hour]) + (float)( 1 - factor)*(history[hour]))/2);
                    estimatedFuture[(hour+1)%24] += (float)(((float)factor*(l) + (float)( 1 - factor)*(history[(hour+1)%24]))/2);
                }
                
                //see if still   downloading is continuing. If so sleep for the next hour slot
                /*       This information is there in table status of downloads database
                 *       The states of the values corresp to key = 0 ( 0 is for the java 1 for python files on server)
                 *                  -1       The java program is sleeping.
                 *                   0       The java program is currently processing the information
                 *                   1       The java program has completed the analysis indicating python to download files
                 *                   
                 * The java program waits for python to complete downloading the files - key 1 
                 *                  0        Python is downloading some file
                 *                  1        Python is waiting for next download(s).
                */
                 
                read = "select * from status where kindof ='1'";
                rs =readStmt.executeQuery(read);
                if(rs.next()){
                    if(rs.getInt("value")==0)
                        continue;
                }
                
                
                if((Float.compare(history[hour],(float)-10))>0){
                    //extract min from record tree
                    putDownload(readStmt,writeStmt);
                    // now put more for downloads if future is feasable to do so
                    if(!initially){//it is not the  first time of running of server so  history consideration is taken.
                        //see if more downloads are possible
                        if((Float.compare(estimatedFuture[(hour+1)%24],(float)500))>0){
                            //put one more download...
                            
                            putDownload(readStmt,writeStmt);
                            if((Float.compare(estimatedFuture[(hour+2)%24],(float)600))>0){
                                //put one more download.......
                                
                                putDownload(readStmt,writeStmt);
                                if((Float.compare(estimatedFuture[(hour+3)%24],(float)600))>0){
                                    //put one for download..........
                                    
                                    putDownload(readStmt,writeStmt);
                                }
                            }
                            
                        }
                    }
                    write = "update status set value = '1' where kindof = '0'";
                    writeStmt.execute(write);
                }
               
                
            }
            catch (Exception e){
                System.out.println("Deadly error occured!!");
                throw e;
            }
            finally {
                 //update the time for the hour
                //System.out.println("hi in finally"+hour);
                if(initially){
                    hour++;
                    if(hour==24){
                        initially = false;
                        hour =0;
                        break;
                        //hour =0;
                    }
                }
                else{
                    hour = (hour+1)%24 ;
                }
                write = "update status set value = '-1' where kindof ='0' ";
                writeStmt.execute(write);
                // sleep the thread for next hour slot...
                // WIP.....
                try{
                    
                }
                catch(Exception e){
                    
                }
            }
        }
         
        
    }
    
    static void putDownload(Statement stmt,Statement wstmt) throws Exception{
        //WIP
        ResultSet rs;
        String read,execute;
        
        DownloadRecord min = recordTree.poll();
        if(min == null){
            System.out.println("System Log :: There are no more downloads to be scheduled!!!");
            return;
        }
        read = "select * from downloads where url ='" + min.url +"'";
        rs = stmt.executeQuery(read);
        if(rs.next()){
            execute = "update downloads set priority = '"+ (min.priority/100) +"' where url ='"+min.url+"'";
            wstmt.execute(execute);
            String pythonScriptPath = "hello.py";
            String[] cmd = new String[2];
            cmd[0] = "python";
            cmd[1] = pythonScriptPath;
 
// create runtime to execute external command
            Runtime rt = Runtime.getRuntime();
            Process pr = rt.exec(cmd);
 
// retrieve output from python script
            BufferedReader bfr = new BufferedReader(new InputStreamReader(pr.getInputStream()));
            String line = "";
            while((line = bfr.readLine()) != null) {
// display each output line form python script
                System.out.println(line);
            }
             //ProcessBuilder pb = new ProcessBuilder("cmd","idman.exe", "/n", "/d","http://intranet.iith.ac.in/software/scientific/scilab/scilab-5.2.1.exe");
            //Process p = Runtime.getRuntime().exec("idman /n /d http://intranet.iith.ac.in/software/scientific/scilab/scilab-5.2.1.exe");
            //BufferedWriter writer = new BufferedWriter(new OutputStreamWriter(p.getOutputStream()));
            //writer.write("password");
            //writer.newLine();
            //writer.flush();
            //writer.close();
             //Process p = pb.start();
        }
    }
    
    static void addNewDownloads(Statement stmt,Statement wstmt) throws Exception {
        ResultSet rs;
        DownloadRecord tem,tem1;
        int hour,minute,timePrio,sizePrio,temp,temp1,temp2;
        rs = null;
        String query,execute,time,tokens[];
        int entries=0;
        tem = new DownloadRecord();
        Date date = new Date();// given date
        Calendar calendar = GregorianCalendar.getInstance(); // creates a new calendar instance
        calendar.setTime(date);   // assigns calendar to given date 
            
            
        query="select * from downloads where status = '3' ";
        rs = stmt.executeQuery(query);
        while(rs.next()){
            entries++;
            //System.out.println("the entry url is "+rs.getString("url")+"  and file name is "+rs.getString("name"));
            //insert the intry into the recordtree after processing. 
            
            hour = calendar.get(Calendar.HOUR_OF_DAY);
            minute = calendar.get(Calendar.MINUTE);
            time = rs.getString("time");
            tokens = time.split(":",3);
            temp = Integer.parseInt(tokens[0])*60;
            temp += Integer.parseInt(tokens[1]);
            temp -= hour*60 + minute;
            if(temp < 0)
                temp += 24*60;
            temp *= 100;
            timePrio = temp /(24 *60);
            sizePrio = rs.getInt("size")*100;
            sizePrio = sizePrio /(2000);
            temp1 = (sizePrio + timePrio)*100;
            temp2 = temp1 + 100;
            tem1 = new DownloadRecord();
            tem1.priority = temp1;
            tem1.url = null;
            for(DownloadRecord ite :recordTree){
                if((ite.priority>=temp1)&&(ite.priority<temp2 -1)){
                    if(ite.priority>tem1.priority)
                        tem1 = ite;
                }
            }
            if(!(tem1.url==null)){
                tem.priority = tem1.priority + 1;
                tem.url = rs.getString("url");
                if(!recordTree.add(tem))
                    System.out.println("System Log:: Error in addition of the entry  "+tem.url);
                else
                    System.out.println("System Log :: Add the entry "+tem.url);
            }
            else{
                tem.url =rs.getString("url");
                tem.priority=tem1.priority;
                if(!recordTree.add(tem))
                    System.out.println("System Log:: Error in addition of the entry  "+tem.url);
                else
                    System.out.println("System Log :: Add the entry "+tem.url);
            }
            //update the status in the database.
            execute="update downloads set status = '0' where url ='"+rs.getString("url")+"' ";
            wstmt.execute(execute);
            
        }
        System.out.println("System Log:: "+entries+" entries have been added to the scheduling Download list");
    }
    
    static void deleteDownloads(Statement stmt,Statement wstmt) throws Exception {
        ResultSet rs=null;
        String query,execute;
        int entries=0;
        
        //the entries with status 5 are to be deleted as processing and notiying are already done
        query="select * from downloads where status = '5' ";
        rs = stmt.executeQuery(query);
        while(rs.next()){
            entries++;
            //System.out.println("the entry url is "+rs.getString("url")+"  and file name is "+rs.getString("name"));
            
            execute="delete from downloads where url ='"+rs.getString("url")+"' ";
            wstmt.execute(execute);
        }
        System.out.println("System Log:: "+entries+" entries have been deleted due to error or  completion");
    }
    
    static void updateDownloadPriority(Statement stmt,Statement wstmt) throws Exception {
        int entries =0;
        
        
        for(DownloadRecord ite:recordTree){
            entries++;
            if(ite.priority <= 5){
                RunServer = false;
                System.out.println("System Log:: The network is too bad...........stopping the services. Restart the services later");
                return;
            }
                
            if(ite.priority > 1000)
                ite.priority -= 416 ;
            else
                ite.priority -= 5;
        }
        System.out.println("System Log:: "+entries+"  entries have been updated");
        
    }
    
    
    static float testInternet() throws IOException {
        float bd;
        URL website = new URL("http://fc05.deviantart.net/fs7/i/2005/172/f/3/Island_Beach_by_brokenfish.png");//+"?n="+Math.random());
        InputStream is = null;
        FileOutputStream fos = null;

        try {
                URLConnection urlConn = website.openConnection();//connect
                is = urlConn.getInputStream();               //get connection inputstream
                fos = new FileOutputStream("a.txt");   //open outputstream to local file

                byte[] buffer = new byte[4096];              //declare 4KB buffer
                int len;

        //while we have availble data, continue downloading and storing to local file
                Long start = System.nanoTime();

                while ((len = is.read(buffer)) > 0) {
                    fos.write(buffer, 0, len);
                }
                
                Long end = System.nanoTime();
        
                long time = end-start;
                bd= (float)1000000000/(float)time;
                bd= bd*2142208;
                bd=bd/1024;
                bd=bd*8;
                System.out.println("Sytem Log:: Test Internet:: The Bandwidth is " + bd);
        } finally {
                try {
                    if (is != null) {
                        is.close();
                    }
                } finally {
                    if (fos != null) {
                        fos.close();
                    }
                }
        }
        return bd;
    }
    
    
}
