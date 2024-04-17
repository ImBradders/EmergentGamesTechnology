using System.Net.Sockets;
using System.Text;

namespace SocketServer
{
    class ConnectionHandler
    {
        private bool _isAlive;
        private readonly Socket _socket;

        public ConnectionHandler(Socket connection) { 
            _socket = connection;
            _isAlive = true;
        }

        public void Run()
        {
            string userInput, dataReceived;
            while (_isAlive)
            {
                Console.WriteLine("Please enter an axis to move in using the format axis:amount");
                userInput = Console.ReadLine().Trim();
                if (userInput.Length == 0)
                    userInput = " ";
                switch (userInput[0])
                {
                    case 'q':
                        //Quit
                        SendMessage("q");
                        _isAlive = false;
                        break;
                    case 'x':
                    case 'y':
                    case 'z':
                        //move in an axis
                        if (userInput[1] != ':')
                            Console.WriteLine("Entered in wrong format.");
                        if (!int.TryParse(userInput[2..], out _))
                            Console.WriteLine("Data after : was NaN");
                        SendMessage(userInput);
                        break;
                    default:
                        //command not supported.
                        if (userInput != "")
                            Console.WriteLine("Command not supported.");
                        break;
                }
                dataReceived = ReceiveMessage();
                if (dataReceived != null)
                {
                    if (dataReceived == "q")
                    {
                        SendMessage("q");
                        _isAlive = false;
                    }
                    else
                    {
                        Console.WriteLine(dataReceived);
                    }
                }
            }
        }

        /// <summary>
        /// Attempts to send a message across the network.
        /// </summary>
        /// <param name="message">The message to be sent.</param>
        /// <returns>Whether the message was sent.</returns>
        private bool SendMessage(string message)
        {
            try
            {
                byte[] buffer = new byte[message.Length];
                int bytesToSend = Encoding.UTF8.GetBytes(message, 0, message.Length, buffer, 0);
                _socket.Send(buffer, bytesToSend, SocketFlags.None);
            }
            catch (Exception e)
            {
                if (_isAlive)
                    Console.WriteLine(e.ToString());
                return false;
            }
            return true;
        }

        /// <summary>
        /// Attempts to receive a message from the network.
        /// </summary>
        /// <returns>The message from the network or null if no message was present.</returns>
        private string? ReceiveMessage()
        {
            if (_socket.Available == 0)
            {
                return null;
            }

            string dataReceived;
            try
            {
                byte[] buffer = new byte[2048];
                int bytesReceived = _socket.Receive(buffer);
                dataReceived = Encoding.UTF8.GetString(buffer, 0, bytesReceived);
            }
            catch (Exception e)
            {
                if (_isAlive)
                    Console.WriteLine(e.ToString());
                return null;
            }

            return dataReceived;
        }
    }
}
