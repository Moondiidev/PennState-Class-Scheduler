import React from 'react';
import ReactDOM from 'react-dom';

class Navigation extends React.Component {

    constructor(props){
        super(props);
        this.nav = {
            'links' : JSON.parse(this.props.nav)
        }

        this.activeLink = "Home";
        if (window.location.href.indexOf("completed") !== -1) {this.activeLink = "Completed"; }
        else if (window.location.href.indexOf("recommendations") !== -1) {this.activeLink = "Recommends";}
        else if (window.location.href.indexOf("courses") !== -1) {this.activeLink = "Courses";}

    };


    render(){

        const navLinks = this.nav.links.map( (link, x) => {
            return (
                <li className="mr-6" key={x}>
                    <a className={`${window.location.href.indexOf(link.uri) !== -1 ? 'text-gray-500 cursor-text' : 'text-blue-800 hover:text-blue-600'}`} href={`${link.uri}`}>{link.name}</a>
                </li>
            )
        });

        return (
            <ul className="flex px-6 py-2 border-b-2 mb-4">
                {navLinks}
            </ul>
        );
    }
}

export default Navigation;

if (document.getElementById('navigation')) {

    // get props
    const data = document.getElementById('navigation').getAttribute('data');

    ReactDOM.render(<Navigation nav={data}/>, document.getElementById('navigation'));
}
