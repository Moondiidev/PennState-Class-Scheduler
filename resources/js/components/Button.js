import React from 'react';
import ReactDOM from 'react-dom';

class Button extends React.Component {

    render(){

        const buttonText = "Click me";

        return (
            <div className="mt-6">
                <button onClick={showItsAReactComponent} className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {buttonText}
                </button>
            </div>
        );

        function showItsAReactComponent() {
            alert("This is a React component");
        }
    }
}

export default Button;

if (document.getElementById('reactButton')) {
    ReactDOM.render(<Button/>, document.getElementById('reactButton'));
}
