// See https://aka.ms/new-console-template for more information
using SocketServer;
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

ConnectionHandler connection = new ConnectionHandler(connect);
connection.Run();