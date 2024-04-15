// See https://aka.ms/new-console-template for more information
using System.Net;
using System.Net.Sockets;
using System.Text;

Console.WriteLine("Running!");
IPAddress myIP = IPAddress.Parse("127.0.0.1");
IPEndPoint myEndPoint = new IPEndPoint(myIP, 50001);
Socket mySocket = new Socket(myIP.AddressFamily, SocketType.Stream, ProtocolType.Tcp);

mySocket.Bind(myEndPoint);
mySocket.Listen();

Socket connect = mySocket.Accept();

try
{
    string message = "hello, client!";
    byte[] buffer = new byte[message.Length];
    int bytesToSend = Encoding.UTF8.GetBytes(message, 0, message.Length, buffer, 0);
    connect.Send(buffer, bytesToSend, SocketFlags.None);
}
catch (Exception e)
{
    Console.WriteLine(e.ToString());
}

try
{
    byte[] buffer = new byte[2048];
    int bytesReceived = connect.Receive(buffer);
    string toProcess = Encoding.UTF8.GetString(buffer, 0, bytesReceived);
    Console.WriteLine(toProcess);
}
catch (Exception e)
{
    Console.WriteLine(e.ToString());
}